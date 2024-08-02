<?php

declare(strict_types=1);

return [
    'base_path' => 'base',
    'default' => [
        'Some/Default/Namespace',
    ],
    'modules' => [
        [
            'module' => 'module',
            'exclude' => ['tests'],
        ],
    ],
];
