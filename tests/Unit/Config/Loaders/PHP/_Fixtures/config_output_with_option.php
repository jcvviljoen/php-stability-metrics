<?php

declare(strict_types=1);

return [
    'base_path' => 'base',
    'modules' => [
        [
            'name' => 'BaseValid',
            'path' => 'base/module',
            'threshold_zone_of_pain' => 0.2,
            'threshold_zone_of_uselessness' => 0.8,
            'exclude' => ['tests'],
        ],
    ],
    'output' => [
        'option' => 'json',
        'fileName' => 'some-output',
        'filePath' => '/var/logs',
    ],
];
