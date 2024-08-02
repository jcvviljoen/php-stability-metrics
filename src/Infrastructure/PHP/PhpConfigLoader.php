<?php

declare(strict_types=1);

namespace Stability\Infrastructure\PHP;

use Override;
use Stability\Config\Config;
use Stability\Config\ConfigLoader;
use Stability\Config\Module\Module;
use Stability\Exception\InvalidConfigurationException;

readonly class PhpConfigLoader implements ConfigLoader
{
    #[Override] public function load(string $path): Config
    {
        if (!file_exists($path)) {
            throw InvalidConfigurationException::onMissingConfigFile($path);
        }

        $config = include $path;

        /** @var string $basePath */
        $basePath = $config['base_path'] ?? throw InvalidConfigurationException::onMissingBasePath();
        /** @var array<string> $default */
        $default = $config['default'] ?? [];
        /** @var array<int, array<string, mixed>> $modules */
        $modules = $config['modules'];

        if (empty($modules)) {
            throw throw InvalidConfigurationException::onMissingModules();
        }

        $modules = array_map(
            function (array $moduleConfig) {
                /** @var string $modulePath */
                $modulePath = $moduleConfig['module'] ?? throw InvalidConfigurationException::onMissingModule();
                /** @var array<string> $exclude */
                $exclude = $moduleConfig['exclude'] ?? [];

                return new Module(
                    $modulePath,
                    $exclude,
                );
            },
            $modules,
        );

        return new Config($basePath, $default, $modules);
    }
}
