<?php

namespace App\Http\Controllers\Concerns;

use App\Models\LeaveRequest\Enums\LeaveRequestType;
use App\Models\LeaveRequest\LeaveRequest;
use Carbon\Carbon;

trait FormatsLeaveRequestsForCalendar
{
    /**
     * @return array<string, mixed>
     */
    protected function leaveRequestToFullCalendarEvent(LeaveRequest $leave): array
    {
        $name = $leave->user?->name ?? __('label.user');
        $typeLabel = $leave->type ? __('leaveRequest.type.' . $leave->type) : '';
        $title = __('leaveRequest.calendar_event_title', [
            'name' => $name,
            'type' => $typeLabel,
        ]);

        [$bg, $border] = match ($leave->type) {
            LeaveRequestType::VACATION => ['#1b5e20', '#0d3d14'],
            LeaveRequestType::SICK_LEAVE => ['#e65100', '#bf360c'],
            LeaveRequestType::DAY_OFF => ['#1565c0', '#0d47a1'],
            default => ['#37474f', '#263238'],
        };

        $start = Carbon::parse($leave->start_date)->format('Y-m-d');
        $endExclusive = Carbon::parse($leave->end_date)->addDay()->format('Y-m-d');

        return [
            'id' => 'leave-' . $leave->id,
            'title' => $title,
            'start' => $start,
            'end' => $endExclusive,
            'allDay' => true,
            'editable' => false,
            'display' => 'block',
            'backgroundColor' => $bg,
            'borderColor' => $border,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'isLeave' => true,
                'leaveType' => $leave->type,
            ],
        ];
    }
}
