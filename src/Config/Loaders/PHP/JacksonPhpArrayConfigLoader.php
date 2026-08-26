<?php

declare(strict_types=1);

namespace Stability\Config\Loaders\PHP;

use Override;
use Stability\Config\ConfigLoader;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\StabilityConfig;
use Stability\Shared\StabilityException;
use Tcds\Io\Jackson\ArrayObjectMapper;
use Throwable;

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

        try {
            return $this->mapper->readValue(StabilityConfig::class, $config);
        } catch (Throwable $exception) {
            throw $this->asStabilityException($path, $exception);
        }
    }

    /**
     * The mapper builds the config objects through reflection and wraps anything they
     * throw, so our own validation ends up buried in the exception chain. Everything
     * else it cannot map (an unknown enum value, say) it reports as a raw error, which
     * would otherwise reach the user as a fatal.
     */
    private function asStabilityException(string $path, Throwable $exception): StabilityException
    {
        for ($cause = $exception; null !== $cause; $cause = $cause->getPrevious()) {
            if ($cause instanceof StabilityException) {
                return $cause;
            }
        }

        return InvalidConfigurationException::onUnreadableConfiguration($path, $exception);
    }
}
