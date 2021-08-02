<?php

return [
    'debug' => 1,

    'log' => [
        'default' => [
            'name' => 'default-logger',
            'handler' => [
                'file' => BASE_DIR . '/storage/log/default.log',
                'level' => 100,
            ],
        ],
    ],

    'templates' => BASE_DIR . '/app/Templates',

    'error' => [
        'templates' => [
            'default' => 'error/default.phtml',
        ],
    ],

    'sessions' => [
        'options' => [
//            'save_path' => '127.0.0.1:11211',
            'save_path' => BASE_DIR . '/storage/sessions',
            'gc_maxlifetime' => '1800',
        ],
        'session_save_handler' => [

        ],
    ],

    'db' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'dbname' => BASE_DIR . '/storage/sqlite/example.db',
            'user' => '',
            'password' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'example',
            'user' => 'guest',
            'password' => 'secret',
        ],
    ]
];
