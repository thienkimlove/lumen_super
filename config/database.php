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
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'external' => [
            'driver' => 'mysql',
            'host' => '103.7.41.141',
            'database' => 'virtuals',
            'username' => 'tieungao',
            'password' => 'tieungao123',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ]
];