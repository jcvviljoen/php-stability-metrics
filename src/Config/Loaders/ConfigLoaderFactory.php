<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Stability\Config\ConfigLoader;
use Stability\Config\ConfigType;
use Stability\Config\Loaders\PHP\JacksonPhpArrayConfigLoader;

readonly class ConfigLoaderFactory
{
    public static function create(ConfigType $type): ConfigLoader
    {
        return match ($type) {
            ConfigType::PHP_ARRAY => new JacksonPhpArrayConfigLoader(),
        };
    }
}
