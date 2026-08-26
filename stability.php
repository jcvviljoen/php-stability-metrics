<?php

declare(strict_types=1);

/**
 * Stability's own configuration, run against itself by `composer test:stability`.
 *
 * Every directory under src is listed, so that no file escapes the analysis. Adding a new
 * component means adding it here too, and CI fails on a circular dependency between any
 * of them.
 */
return [
    'modules' => [
        [
            'name' => 'Application',
            'path' => 'src/Application',
        ],
        [
            'name' => 'Chart',
            'path' => 'src/Chart',
        ],
        [
            'name' => 'Component',
            'path' => 'src/Component',
        ],
        [
            'name' => 'Config',
            'path' => 'src/Config',
        ],
        [
            'name' => 'Console',
            'path' => 'src/Console',
        ],
        [
            'name' => 'Graph',
            'path' => 'src/Graph',
        ],
        [
            'name' => 'Metric',
            'path' => 'src/Metric',
        ],
        [
            'name' => 'Output',
            'path' => 'src/Output',
        ],
        [
            'name' => 'Shared',
            'path' => 'src/Shared',
        ],
    ],
];
