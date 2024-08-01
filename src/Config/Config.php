<?php

namespace Stability\Config;

use Stability\Exception\InvalidConfigurationException;

readonly class Config
{
    private const string PATH_SEPARATOR = '/';

    /**
     * @param array<string> $default
     * @param array<Module> $modules
     */
    private function __construct(
        public string $basePath,
        public array $default,
        public array $modules,
    ) {
    }

    /**
     * @param array<string, mixed> $config
     *
     * @throws InvalidConfigurationException
     */
    public static function from(array $config): self
    {
        /** @var string $basePath */
        $basePath = $config['basePath'] ?? throw InvalidConfigurationException::onMissingBasePath();
        /** @var array<string> $default */
        $default = $config['default'] ?? [];
        /** @var array<int, array<string, mixed>> $modules */
        $modules = $config['modules'] ?? throw InvalidConfigurationException::onMissingModules();

        $modules = array_map(
            function (array $moduleConfig) use ($basePath) {
                /** @var string $module */
                $module = $moduleConfig['module'] ?? throw InvalidConfigurationException::onMissingModule();
                /** @var array<string> $exclude */
                $exclude = $moduleConfig['exclude'] ?? [];

                return new Module(
                    $module,
                    $basePath . DIRECTORY_SEPARATOR . $module,
                    $exclude,
                );
            },
            $modules,
        );

        return new self($basePath, $default, $modules);
    }
}
