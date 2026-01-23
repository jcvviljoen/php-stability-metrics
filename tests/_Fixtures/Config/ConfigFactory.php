<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;
use Stability\Config\Loaders\LoadedConfig;
use Stability\Config\ModuleList;
use Stability\Tests\_Fixtures\Output\OutputSettingFactory;

readonly class ConfigFactory
{
    public static function testSource(): Config
    {
        return new LoadedConfig(
            new ModuleList([
                ModuleFactory::module1(),
                ModuleFactory::module2(),
                ModuleFactory::module3(),
            ]),
            OutputSettingFactory::default(),
        );
    }

    public static function module1(): Config
    {
        return new LoadedConfig(
            new ModuleList([ModuleFactory::module1()]),
            OutputSettingFactory::default(),
        );
    }

    public static function unknown(): Config
    {
        return new LoadedConfig(
            new ModuleList([ModuleFactory::unknown()]),
            OutputSettingFactory::default(),
        );
    }

    /**
     * @see tests/Unit/Infrastructure/PHP/_Fixtures/Files/config_valid.php
     */
    public static function baseValid(): Config
    {
        return new LoadedConfig(
            new ModuleList([ModuleFactory::baseValid()]),
            OutputSettingFactory::default(),
        );
    }
}
