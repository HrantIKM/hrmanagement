<?php

namespace App\Notifications;

use App\Models\LeaveRequest\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class LeaveRequestDecisionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public LeaveRequest $leaveRequest,
        public string $status
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

        $isApproved = $this->status === LeaveRequestStatus::APPROVED;

        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->status,
            'title' => $isApproved
                ? __('notifications.leave_request_approved_title')
                : __('notifications.leave_request_rejected_title'),
            'message' => $isApproved
                ? __('notifications.leave_request_approved_body', [
                    'start' => $this->leaveRequest->start_date?->format('Y-m-d') ?? '',
                    'end' => $this->leaveRequest->end_date?->format('Y-m-d') ?? '',
                ])
                : __('notifications.leave_request_rejected_body', [
                    'start' => $this->leaveRequest->start_date?->format('Y-m-d') ?? '',
                    'end' => $this->leaveRequest->end_date?->format('Y-m-d') ?? '',
                ]),
            'url' => route('dashboard.leave-requests.show', $this->leaveRequest->id),
        ];
    }
}
