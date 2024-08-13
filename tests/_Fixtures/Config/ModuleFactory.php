<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Module\Module;

class ModuleFactory
{
    public static function module1(): Module
    {
        return new Module(
            'Module1',
            0.7,
            0.7,
            [],
        );
    }

    public static function module2(): Module
    {
        return new Module(
            'Module2',
            0.7,
            0.7,
            [],
        );
    }

    public static function module3(): Module
    {
        return new Module(
            'Module3',
            0.7,
            0.7,
            [],
        );
    }

    public static function unknown(): Module
    {
        return new Module(
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
        return new Module(
            'module',
            0.2,
            0.8,
            ['tests'],
        );
    }
}
