<?php

declare(strict_types=1);

return [
    'base_path' => 'base',
    'modules' => [
        [
            'name' => 'BaseValid',
            'path' => 'base/module',
            'thresholdZoneOfPain' => 0.2,
            'thresholdZoneOfUselessness' => 0.8,
            'exclude' => ['tests'],
        ],
    ],
    'outputSettings' => [
        'option' => 'json',
        'fileName' => 'some-output',
        'filePath' => '/var/logs',
    ],
];
