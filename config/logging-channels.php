<?php

return [
    'dl_file' => [
        'driver' => 'daily',
        'path' => storage_path('logs/dl_sql.log'),
        'level' => 'debug',
        'days' => 30,
        'ignore_exceptions' => true,
    ],
];