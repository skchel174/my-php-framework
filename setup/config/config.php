<?php

return [
    'debug' => false,

    'log' => [
        'name' => 'default_log',
        'file' => BASE_DIR . '/storage/log/default.log',
    ],

    'templates' => BASE_DIR . '/app/Templates',

    'error' => [
        'templates' => [
            'default' => 'error/default.phtml',
        ],
    ]
];
