<?php

declare(strict_types=1);

namespace Stability\Config\Loaders\PHP;

use Override;
use Stability\Config\ConfigLoader;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\StabilityConfig;
use Tcds\Io\Jackson\ArrayObjectMapper;

readonly class JacksonPhpArrayConfigLoader implements ConfigLoader
{
    private ArrayObjectMapper $mapper;

    public function __construct()
    {
        $this->mapper = new ArrayObjectMapper();
    }

    #[Override]
    public function load(string $path): StabilityConfig
    {
        if (!file_exists($path)) {
            throw InvalidConfigurationException::onMissingConfigFile($path);
        }

        $config = include $path;

        return $this->mapper->readValue(StabilityConfig::class, $config);
    }
}
