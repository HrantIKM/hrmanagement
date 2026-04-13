<?php

return [
    'requesting_employee' => 'Employee',
    'requesting_employee_help' => 'This request is sent to HR for approval. You cannot change status yourself; only administrators can approve or reject.',
    'requesting_employee_help_admin' => 'This is the employee this leave request belongs to. New requests are always created for your own account.',
    'status_readonly_employee' => 'Pending — waiting for administrator review.',
    'approve' => 'Approve',
    'reject' => 'Reject',
    'calendar_event_title' => ':name — :type',
    'calendar_hint' => 'Approved leave and holidays appear as all-day blocks (leave by type). Meetings stay editable; leave and holiday blocks are read-only.',

    'type' => [
        'vacation' => 'Vacation',
        'sick_leave' => 'Sick leave',
        'day_off' => 'Day off',
    ],

    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],
];
