<?php

namespace App\Services\Meeting;

use App\Contracts\Meeting\IMeetingRepository;
use App\Contracts\User\IUserRepository;
use App\Models\Meeting\Enums\MeetingStatus;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MeetingService extends BaseService
{
    public function __construct(
        IMeetingRepository $repository,
        protected IUserRepository $userRepository
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
            'meetingUserIds' => $meetingUserIds,
            'meetingStatusOptions' => collect(MeetingStatus::ALL)
                ->mapWithKeys(fn (string $v) => [$v => __('meeting.status.' . $v)]),
        ];
    }

    public function createOrUpdate(array $data, ?int $id = null): Model
    {
        $userIds = array_key_exists('user_ids', $data) ? $data['user_ids'] : null;
        unset($data['user_ids']);

        return DB::transaction(function () use ($data, $id, $userIds) {
            $meeting = $id
                ? $this->repository->update($id, $data)
                : $this->repository->create($data);

            if ($userIds !== null) {
                $meeting->participants()->sync($userIds);
            }

            return $meeting->refresh();
        });
    }
}
