<?php

return [
    'date_format' => env('DATE_FORMAT', 'Y-m-d'),
    'date_format_front' => env('DATE_FORMAT_FRONT', 'd.m.Y'),
    'date_time_format' => env('DATE_TIME_FORMAT', 'Y-m-d H:i:s'),
    'date_time_format_front' => env('DATE_TIME_FORMAT_FRONT', 'd.m.Y H:i'),

    'js' => [
        'date_format' => env('JS_DATE_FORMAT', 'YYYY-MM-DD'),
        'date_format_front' => env('JS_DATE_FORMAT_FRONT', 'DD.MM.YYYY'),
        'date_time_format' => env('JS_DATE_TIME_FORMAT', 'YYYY-MM-DD HH:mm:ss'),
        'date_time_format_front' => env('JS_DATE_TIME_FORMAT_FRONT', 'DD.MM.YYYY HH:mm'),
    ],

    'show_notification' => env('SHOW_NOTIFICATION', true),
];
