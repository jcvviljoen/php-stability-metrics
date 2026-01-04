<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Loaders\LoadedModule;
use Stability\Config\Module;

readonly class ModuleFactory
{
    public static function module1(): Module
    {
        return new LoadedModule(
            'Module1',
            0.7,
            0.7,
            [],
        );
    }

    public static function module2(): Module
    {
        return new LoadedModule(
            'Module2',
            0.7,
            0.7,
            [],
        );
    }

    public static function module3(): Module
    {
        return new LoadedModule(
            'Module3',
            0.7,
            0.7,
            [],
        );
    }

    public static function unknown(): Module
    {
        return new LoadedModule(
            'Unknown',
            0.7,
            0.7,
            [],
        );
    }

    /**
     * @see tests/Unit/Infrastructure/PHP/_Fixtures/Files/config_valid.php
     */
    public static function baseValid(): Module
    {
        return new LoadedModule(
            'base/module',
            0.2,
            0.8,
            ['tests'],
        );
    }
}
