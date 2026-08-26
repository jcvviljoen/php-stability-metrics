<?php

declare(strict_types=1);

// Points at the test source tree, so a full run can be driven end to end. Module paths
// are read relative to the working directory, which for the suite is this project's root.
return [
    'modules' => [
        [
            'name' => 'Module1',
            'path' => 'tests/_Fixtures/_TestSrc/Module1',
        ],
        [
            'name' => 'Module2',
            'path' => 'tests/_Fixtures/_TestSrc/Module2',
        ],
        [
            'name' => 'Module3',
            'path' => 'tests/_Fixtures/_TestSrc/Module3',
        ],
    ],
];
