<?php
return [
    'default' => 'mysql',
    'migrations' => 'migrations',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        'external' => [
            'driver' => 'mysql',
            'host' => '115.146.127.8',
            'database' => 'virtuals',
            'username' => 'tieungao',
            'password' => 'tieungao123',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ]
];