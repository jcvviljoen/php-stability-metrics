<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Loaders\LoadedModule;

readonly class LoadedModuleFactory
{
    public static function module1(): LoadedModule
    {
        return new LoadedModule(
            'Module1',
            'Module1',
            0.7,
            0.7,
            [],
        );
    }

    public static function module2(): LoadedModule
    {
        return new LoadedModule(
            'Module2',
            'Module2',
            0.7,
            0.7,
            [],
        );
    }

    public static function module3(): LoadedModule
    {
        return new LoadedModule(
            'Module3',
            'Module3',
            0.7,
            0.7,
            [],
        );
    }

    public static function unknown(): LoadedModule
    {
        return new LoadedModule(
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
    public static function baseValid(): LoadedModule
    {
        return new LoadedModule(
            'BaseValid',
            'base/module',
            0.2,
            0.8,
            ['tests'],
        );
    }
}
