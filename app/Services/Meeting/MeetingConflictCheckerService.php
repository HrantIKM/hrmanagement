<?php

namespace App\Services\Meeting;

use App\Models\Meeting\Meeting;

class MeetingConflictCheckerService
{
    public function hasRoomConflict(?int $meetingId, ?int $roomId, string $startAt, string $endAt): bool
    {
        if ($roomId === null || $roomId === 0) {
            return false;
        }

        return Meeting::query()
            ->where('room_id', $roomId)
            ->when($meetingId, fn ($q) => $q->where('id', '!=', $meetingId))
            ->where(function ($q) use ($startAt, $endAt) {
                $q->where('start_at', '<', $endAt)
                    ->where('end_at', '>', $startAt);
            })
            ->exists();
    }

    public function hasUserConflict(?int $meetingId, array $userIds, string $startAt, string $endAt): bool
    {
        if (empty($userIds)) {
            return false;
        }

        return Meeting::query()
            ->whereHas('participants', fn ($q) => $q->whereIn('users.id', $userIds))
            ->when($meetingId, fn ($q) => $q->where('id', '!=', $meetingId))
            ->where(function ($q) use ($startAt, $endAt) {
                $q->where('start_at', '<', $endAt)
                    ->where('end_at', '>', $startAt);
            })
            ->exists();
    }
}
