<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;
use Stability\Config\OutputSetting;
use Stability\Config\StabilityConfig;
use Stability\Config\StabilityModuleList;

readonly class StabilityConfigFactory
{
    /**
     * @see tests/Unit/Config/Loaders/PHP/_Fixtures/config_valid.php
     */
    public static function baseValid(?OutputSetting $outputSettings = null): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([StabilityModuleFactory::baseValid()]),
            $outputSettings ?? OutputSettingFactory::default(),
        );
    }
}
