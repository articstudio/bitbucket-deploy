<?php

return [
    'settings' => [
        'logs_name' => 'bitbucket',
        'logs_file' => dirname(__DIR__) . '/logs/app.log',
        'debug_to' => 'example@example.com',
        'debug_subject' => '[DEPLOY] %datetime% - %message%',
        'debug_from' => 'example@example.com',
        'repositories_dir' => dirname(__DIR__) . '/repositories',
        'git_bin' => 'git',
    ],
    'providers' => [
        \App\ExampleCustomProvider::class
    ],
    'middlewares' => [
        \App\ExampleCustomMiddleware::class
    ],
    'repositories' => [
        'REPOSITORY_NAME' => [
            'BRANCH_NAME' => [
                'dir' => '/path/to/code/',
                'cmd' => [],
                'debug' => 'repo_branch_admin@example.com'
            ]
        ]
    ]
];
