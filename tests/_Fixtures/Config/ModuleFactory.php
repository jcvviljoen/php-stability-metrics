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
            [],
        );
    }

    public static function module2(): Module
    {
        return new Module(
            'Module2',
            [],
        );
    }

    public static function module3(): Module
    {
        return new Module(
            'Module3',
            [],
        );
    }

    public static function unknown(): Module
    {
        return new Module(
            'Unknown',
            [],
        );
    }

    public static function baseValid(): Module
    {
        return new Module(
            'module',
            ['tests'],
        );
    }
}
