<?php

use Carbon\Carbon;

if (!function_exists('getDateFormat')) {
    function getDateFormat(): string
    {
        return config('dashboard.date_format');
    }
}

if (!function_exists('getDateFormatFront')) {
    function getDateFormatFront(): string
    {
        return config('dashboard.date_format_front');
    }
}

if (!function_exists('getDateTimeFormat')) {
    function getDateTimeFormat(): string
    {
        return config('dashboard.date_time_format');
    }
}

if (!function_exists('getDateTimeFormatFront')) {
    function getDateTimeFormatFront(): string
    {
        return config('dashboard.date_time_format_front');
    }
}

if (!function_exists('getStartOfDay')) {
    function getStartOfDay(?string $date): Carbon|string
    {
        if (is_null($date)) {
            return '';
        }

        return Carbon::parse($date)->startOfDay();
    }
}

if (!function_exists('getEndOfDay')) {
    function getEndOfDay(?string $date): Carbon|string
    {
        if (is_null($date)) {
            return '';
        }

        return Carbon::parse($date)->endOfDay();
    }
}

if (!function_exists('formattedDate')) {
    function formattedDate(string $date): string
    {
        return Carbon::parse($date)->format(getDateFormatFront());
    }
}

if (!function_exists('formatDateForBackend')) {
    function formatDateForBackend(string $date): string
    {
        $dateObject = DateTime::createFromFormat(getDateFormat(), $date);
        if ($dateObject !== false && $dateObject->format(getDateFormat()) === $date) {
            return $date;
        }

        return $date ? Carbon::createFromFormat(getDateFormatFront(), $date)->format(getDateFormat()) : '';
    }
}

if (!function_exists('formatDateTimeForBackend')) {
    function formatDateTimeForBackend(string $dateTime): string
    {
        $dateObject = DateTime::createFromFormat(getDateTimeFormat(), $dateTime);
        if ($dateObject !== false && $dateObject->format(getDateTimeFormat()) === $dateTime) {
            return $dateTime;
        }

        return $dateTime ? Carbon::createFromFormat(getDateTimeFormatFront(), $dateTime)
            ->format(getDateTimeFormat()) : '';
    }
}

if (!function_exists('getDashboardDates')) {
    function getDashboardDates(): array
    {
        return [
            'date_format' => getDateFormat(),
            'date_format_front' => getDateFormatFront(),
            'date_time_format' => getDateTimeFormat(),
            'date_time_format_front' => getDateTimeFormatFront(),
            'js' => [
                'date_format' => config('dashboard.js.date_format'),
                'date_time_format' => config('dashboard.js.date_time_format'),
                'date_format_front' => config('dashboard.js.date_format_front'),
                'date_time_format_front' => config('dashboard.js.date_time_format_front'),
            ],
        ];
    }
}
