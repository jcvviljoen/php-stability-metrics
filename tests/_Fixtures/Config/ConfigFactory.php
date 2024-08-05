<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;

class ConfigFactory
{
    public static function testSource(): Config
    {
        return new Config(
            'tests/_Fixtures/_TestSrc',
            0.3,
            0.7,
            [],
            [
                ModuleFactory::module1(),
                ModuleFactory::module2(),
                ModuleFactory::module3(),
            ],
        );
    }

    public static function module1(): Config
    {
        return new Config(
            'tests/_Fixtures/_TestSrc',
            0.3,
            0.7,
            [],
            [ModuleFactory::module1()],
        );
    }

    public static function unknown(): Config
    {
        return new Config(
            'src',
            0.3,
            0.7,
            [],
            [ModuleFactory::unknown()],
        );
    }

    public static function baseValid(): Config
    {
        return new Config(
            'base',
            0.2,
            0.8,
            ['Some/Default/Namespace'],
            [ModuleFactory::baseValid()],
        );
    }
}
