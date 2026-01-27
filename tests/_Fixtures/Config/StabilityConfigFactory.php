<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;
use Stability\Config\Loaders\StabilityConfig;
use Stability\Config\Loaders\StabilityModuleList;
use Stability\Tests\_Fixtures\Output\OutputSettingFactory;

readonly class StabilityConfigFactory
{
    public static function testSource(): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([
                StabilityModuleFactory::module1(),
                StabilityModuleFactory::module2(),
                StabilityModuleFactory::module3(),
            ]),
            OutputSettingFactory::default(),
        );
    }

    public static function module1(): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([StabilityModuleFactory::module1()]),
            OutputSettingFactory::default(),
        );
    }

    public static function unknown(): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([StabilityModuleFactory::unknown()]),
            OutputSettingFactory::default(),
        );
    }

    /**
     * @see tests/Unit/Infrastructure/PHP/_Fixtures/Files/config_valid.php
     */
    public static function baseValid(): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([StabilityModuleFactory::baseValid()]),
            OutputSettingFactory::default(),
        );
    }
}
