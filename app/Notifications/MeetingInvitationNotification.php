<?php

namespace App\Notifications;

use App\Models\Meeting\Meeting;
use Illuminate\Notifications\Notification;

class MeetingInvitationNotification extends Notification
{
    public function __construct(
        public Meeting $meeting
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->meeting->loadMissing('room');

        return [
            'type' => 'meeting_invitation',
            'meeting_id' => $this->meeting->id,
            'title' => __('notifications.meeting_invitation_title'),
            'message' => __('notifications.meeting_invitation_body', [
                'title' => $this->meeting->title,
                'time' => $this->meeting->start_at?->format('Y-m-d H:i') ?? '',
            ]),
            'url' => route('dashboard.meetings.show', $this->meeting->id),
        ];
    }
}
