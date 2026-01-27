<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Loaders\StabilityModule;

readonly class StabilityModuleFactory
{
    public static function module1(): StabilityModule
    {
        return new StabilityModule(
            'Module1',
            'Module1',
            0.7,
            0.7,
            [],
        );
    }

    public static function module2(): StabilityModule
    {
        return new StabilityModule(
            'Module2',
            'Module2',
            0.7,
            0.7,
            [],
        );
    }

    public static function module3(): StabilityModule
    {
        return new StabilityModule(
            'Module3',
            'Module3',
            0.7,
            0.7,
            [],
        );
    }

    public static function unknown(): StabilityModule
    {
        return new StabilityModule(
            'Unknown',
            'Unknown',
            0.7,
            0.7,
            [],
        );
    }

    /**
     * @see tests/Unit/Infrastructure/PHP/_Fixtures/Files/config_valid.php
     */
    public static function baseValid(): StabilityModule
    {
        return new StabilityModule(
            'BaseValid',
            'base/module',
            0.2,
            0.8,
            ['tests'],
        );
    }
}
