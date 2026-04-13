<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Holiday\Holiday;

trait FormatsHolidaysForCalendar
{
    /**
     * @return array<string, mixed>
     */
    protected function holidayToFullCalendarEvent(Holiday $holiday): array
    {
        return [
            'id' => 'holiday-' . $holiday->id,
            'title' => __('holiday.calendar_event_title', ['name' => $holiday->name]),
            'start' => $holiday->date?->format('Y-m-d'),
            'allDay' => true,
            'editable' => false,
            'display' => 'block',
            'backgroundColor' => '#b91c1c',
            'borderColor' => '#7f1d1d',
            'textColor' => '#ffffff',
            'extendedProps' => [
                'isHoliday' => true,
                'isPublic' => (bool) $holiday->is_public,
            ],
        ];
    }
}
