<?php

namespace App\Services\Meeting;

use App\Contracts\Meeting\IMeetingRepository;
use App\Contracts\Room\IRoomRepository;
use App\Contracts\User\IUserRepository;
use App\Notifications\MeetingInvitationNotification;
use App\Models\Meeting\Enums\MeetingStatus;
use App\Models\Meeting\Meeting;
use App\Models\Task\Enums\TaskPriority;
use App\Models\Task\Enums\TaskStatus;
use App\Models\Task\Task;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MeetingService extends BaseService
{
    public function __construct(
        IMeetingRepository $repository,
        protected IUserRepository $userRepository,
        protected IRoomRepository $roomRepository,
        protected MeetingConflictCheckerService $conflictCheckerService
    ) {
        $this->repository = $repository;
    }

    public function getViewData(?int $id = null): array
    {
        $meetingUserIds = null;

        if ($id) {
            $meeting = $this->repository->find($id, ['participants']);
            $meetingUserIds = $meeting->participants->pluck('id')->all();
        } else {
            $meeting = $this->repository->getInstance();
        }

        return [
            'meeting' => $meeting,
            'users' => $this->userRepository->getForSelect(),
            'rooms' => $this->roomRepository->getForSelect('name', 'id'),
            'meetingUserIds' => $meetingUserIds,
            'meetingStatusOptions' => collect(MeetingStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('meeting.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $userIds = array_key_exists('user_ids', $data) ? $data['user_ids'] : null;
        unset($data['user_ids']);

        $userIds = is_array($userIds) ? array_values(array_unique(array_map('intval', $userIds))) : [];
        $this->validateConflicts($id, $data, $userIds);

        return DB::transaction(function () use ($data, $id, $userIds) {
            $previous = $id !== null ? $this->repository->find($id, ['participants']) : null;

            $meeting = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if (!empty($userIds)) {
                $meeting->participants()->sync($userIds);
            }

            $meeting = $meeting->refresh();
            $meeting->load(['participants', 'room']);

            if ($this->shouldNotifyParticipants($previous, $meeting, $userIds)) {
                $this->notifyParticipants($meeting, $meeting->participants);
            }

            return $meeting;
        });
    }

    public function convertMinutesToTasks(Meeting $meeting, string $summary): int
    {
        if ($summary === '') {
            return 0;
        }

        $lines = preg_split('/\r\n|\r|\n/', $summary) ?: [];
        $candidateLines = collect($lines)
            ->map(fn ($line) => trim((string) $line))
            ->filter(fn ($line) => preg_match('/^[-*]\s+\[\s*\]\s+/u', $line) === 1 || str_starts_with(strtolower($line), 'todo:'))
            ->map(function ($line) {
                $line = preg_replace('/^[-*]\s+\[\s*\]\s+/u', '', $line) ?? $line;
                $line = preg_replace('/^todo:\s*/iu', '', $line) ?? $line;

                return trim($line);
            })
            ->filter();

        $assigneeId = $meeting->participants()->value('users.id');
        $created = 0;

        foreach ($candidateLines as $line) {
            Task::query()->create([
                'title' => mb_substr($line, 0, 255),
                'description' => "Generated from meeting #{$meeting->id}: {$meeting->title}",
                'priority' => TaskPriority::MEDIUM,
                'status' => TaskStatus::BACKLOG,
                'user_id' => $assigneeId,
            ]);
            $created++;
        }

        return $created;
    }

    private function validateConflicts(?int $meetingId, array $data, array $userIds): void
    {
        $startAt = (string) ($data['start_at'] ?? '');
        $endAt = (string) ($data['end_at'] ?? '');
        $roomId = isset($data['room_id']) && $data['room_id'] !== '' && $data['room_id'] !== null
            ? (int) $data['room_id']
            : null;

        if ($this->conflictCheckerService->hasRoomConflict($meetingId, $roomId, $startAt, $endAt)) {
            throw ValidationException::withMessages([
                'room_id' => [__('meeting.room_already_booked')],
            ]);
        }

        if ($this->conflictCheckerService->hasUserConflict($meetingId, $userIds, $startAt, $endAt)) {
            throw ValidationException::withMessages([
                'user_ids' => ['At least one selected participant already has a meeting at this time.'],
            ]);
        }
    }

    private function shouldNotifyParticipants(?Meeting $before, Meeting $after, array $requestedUserIds): bool
    {
        if ($before === null) {
            return true;
        }

        $normalizeIds = static function (array $ids): array {
            $ids = array_values(array_unique(array_map('intval', $ids)));
            sort($ids);

            return $ids;
        };

        $oldIds = $normalizeIds($before->participants->pluck('id')->all());
        $newIds = !empty($requestedUserIds)
            ? $normalizeIds($requestedUserIds)
            : $oldIds;

        if ($oldIds !== $newIds) {
            return true;
        }

        foreach (['room_id', 'location', 'start_at', 'end_at'] as $field) {
            if ((string) $before->getAttribute($field) !== (string) $after->getAttribute($field)) {
                return true;
            }
        }

        return false;
    }

    private function notifyParticipants(Meeting $meeting, Collection $participants): void
    {
        foreach ($participants as $participant) {
            $participant->notify(new MeetingInvitationNotification($meeting));
        }
    }
}
