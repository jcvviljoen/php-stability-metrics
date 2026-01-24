<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;
use Stability\Config\Loaders\LoadedConfig;
use Stability\Config\Loaders\LoadedModuleList;
use Stability\Tests\_Fixtures\Output\OutputSettingFactory;

readonly class ConfigFactory
{
    public static function testSource(): Config
    {
        return new LoadedConfig(
            new LoadedModuleList([
                LoadedModuleFactory::module1(),
                LoadedModuleFactory::module2(),
                LoadedModuleFactory::module3(),
            ]),
            OutputSettingFactory::default(),
        );
    }

    public static function module1(): Config
    {
        return new LoadedConfig(
            new LoadedModuleList([LoadedModuleFactory::module1()]),
            OutputSettingFactory::default(),
        );
    }

    public static function unknown(): Config
    {
        return new LoadedConfig(
            new LoadedModuleList([LoadedModuleFactory::unknown()]),
            OutputSettingFactory::default(),
        );
    }

    /**
     * @see tests/Unit/Infrastructure/PHP/_Fixtures/Files/config_valid.php
     */
    public static function baseValid(): Config
    {
        return new LoadedConfig(
            new LoadedModuleList([LoadedModuleFactory::baseValid()]),
            OutputSettingFactory::default(),
        );
    }
}
