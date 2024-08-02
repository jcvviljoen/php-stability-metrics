<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Infrastructure\PHP\PhpConfigLoader;

readonly class ConfigLoaderFactory
{
    public static function create(string $filePath): ConfigLoader
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $configType = ConfigType::from($extension);

        return match ($configType) {
            ConfigType::PHP => new PhpConfigLoader(),
        };
    }
}
