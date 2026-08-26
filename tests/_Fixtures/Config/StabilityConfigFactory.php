<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\Config;
use Stability\Config\Loaders\StabilityConfig;
use Stability\Config\Loaders\StabilityModuleList;

readonly class StabilityConfigFactory
{
    /**
     * @see tests/Unit/Config/Loaders/PHP/_Fixtures/config_valid.php
     */
    public static function baseValid(): Config
    {
        return new StabilityConfig(
            new StabilityModuleList([StabilityModuleFactory::baseValid()]),
            OutputSettingFactory::default(),
        );
    }
}
