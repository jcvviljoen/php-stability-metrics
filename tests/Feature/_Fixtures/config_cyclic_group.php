<?php

declare(strict_types=1);

// Three test components knotted together, so that a full run has a group to report on
// rather than a pair of components pointing at each other.
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
        [
            'name' => 'CycleC',
            'path' => 'tests/_Fixtures/_TestSrc/CycleC',
        ],
    ],
];
