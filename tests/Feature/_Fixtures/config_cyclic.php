<?php

declare(strict_types=1);

// Two test components that import each other, so that a full run has a circular
// dependency to detect and report.
return [
    'modules' => [
        [
            'name' => 'CycleA',
            'path' => 'tests/_Fixtures/_TestSrc/CycleA',
        ],
        [
            'name' => 'CycleB',
            'path' => 'tests/_Fixtures/_TestSrc/CycleB',
        ],
    ],
];
