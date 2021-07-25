<?php

return [
    'debug' => true,

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
    ]
];
