<?php

return [
    'room_already_booked' => 'This room is already booked for the selected time range.',

    'summary_help' => [
        'title' => 'How to use the summary',
        'minutes' => 'Write your meeting minutes here in plain language: agenda, discussion, decisions, and context. This text is only stored on the meeting unless you use the formats below for tasks.',
        'action_title' => 'Action items (optional)',
        'action_intro' => 'An action item is a concrete follow-up task. When you use “Convert minutes to tasks”, only lines in the special formats below become tasks in the task board (one task per line). All other lines stay as notes only.',
        'format_checkbox' => 'Checkbox style — start the line with a hyphen or asterisk, an empty checkbox, then the task:',
        'format_todo' => 'Todo style — start the line with “Todo:” (any capitalisation), then the task:',
        'assignee_note' => 'Tasks are assigned to the first user in the participants list below. Set participants before converting if that matters.',
    ],

    'convert_minutes_to_tasks' => 'Convert minutes to tasks',

    'status' => [
        'scheduled' => 'Scheduled',
        'in_progress' => 'In progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],
];
