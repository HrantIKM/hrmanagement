<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait ParsesFullCalendarFeedRange
{
    /**
     * Inclusive start date (Y-m-d) from FullCalendar fetchInfo.startStr.
     */
    protected function fullCalendarVisibleStartDate(Request $request): ?string
    {
        if (!$request->filled('start')) {
            return null;
        }

        $raw = (string) $request->input('start');
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $raw, $m)) {
            return $m[1];
        }

        return Carbon::parse($raw)->toDateString();
    }

    /**
     * FullCalendar end is exclusive; use this as upper bound for DATE columns: where('date', '<', $this).
     */
    protected function fullCalendarVisibleEndExclusiveDate(Request $request): ?string
    {
        if (!$request->filled('end')) {
            return null;
        }

        $raw = (string) $request->input('end');
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $raw, $m)) {
            return $m[1];
        }

        return Carbon::parse($raw)->toDateString();
    }
}
