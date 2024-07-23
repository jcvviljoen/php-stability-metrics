<?php

namespace Instability\Config;

use Instability\Exception\InvalidConfigurationException;

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
        $basePath = $config['basePath'] ?? throw InvalidConfigurationException::onMissingBasePath();
        $default = $config['default'] ?? [];
        $modules = $config['modules'] ?? throw InvalidConfigurationException::onMissingModules();

        $modules = array_map(
            function (array $moduleConfig) use ($basePath) {
                $module = $moduleConfig['module'] ?? throw InvalidConfigurationException::onMissingModule();

                return new Module(
                    $module,
                    $basePath . self::PATH_SEPARATOR . $module,
                    $moduleConfig['exclude'] ?? [],
                );
            },
            $modules
        );

        return new self($basePath, $default, $modules);
    }
}
