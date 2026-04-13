<?php

namespace App\Notifications;

use App\Models\LeaveRequest\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class LeaveRequestPendingNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public LeaveRequest $leaveRequest
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->leaveRequest->loadMissing('user');

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'employee_name' => $this->leaveRequest->user?->name,
            'start_date' => $this->leaveRequest->start_date?->format('Y-m-d'),
            'end_date' => $this->leaveRequest->end_date?->format('Y-m-d'),
            'type' => $this->leaveRequest->type,
            'title' => __('notifications.leave_request_pending_title'),
            'message' => __('notifications.leave_request_pending_body', [
                'employee' => $this->leaveRequest->user?->name ?? '',
            ]),
            'url' => route('dashboard.leave-requests.edit', $this->leaveRequest->id),
        ];
    }
}
