<?php

return [
    'default' => env('QUEUE_CONNECTION', 'database'),
    'connections' => [
        'sync' => ['driver' => 'sync'],
        'database' => ['driver' => 'database', 'connection' => env('DB_QUEUE_CONNECTION'), 'table' => env('DB_QUEUE_TABLE', 'jobs_queue'), 'queue' => env('DB_QUEUE', 'default'), 'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90), 'after_commit' => false],
    ],
    'failed' => ['driver' => env('QUEUE_FAILED_DRIVER', 'null'), 'database' => env('DB_CONNECTION', 'sqlite'), 'table' => 'failed_jobs'],
];
