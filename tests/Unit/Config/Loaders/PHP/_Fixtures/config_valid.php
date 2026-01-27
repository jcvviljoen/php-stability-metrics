<?php

declare(strict_types=1);

return [
    'modules' => [
        [
            'name' => 'BaseValid',
            'path' => 'base/module',
            'thresholdZoneOfPain' => 0.2,
            'thresholdZoneOfUselessness' => 0.8,
            'exclude' => ['tests'],
        ],
    ],
];
