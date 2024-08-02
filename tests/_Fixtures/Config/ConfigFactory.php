<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;

class ConfigFactory
{
    public static function module1(): Config
    {
        return new Config(
            'tests/_Fixtures/_TestSrc',
            [],
            [ModuleFactory::module1()],
        );
    }

    public static function unknown(): Config
    {
        return new Config(
            'src',
            [],
            [ModuleFactory::unknown()],
        );
    }

    public static function baseValid(): Config
    {
        return new Config(
            'base',
            ['Some/Default/Namespace'],
            [ModuleFactory::baseValid()],
        );
    }
}
