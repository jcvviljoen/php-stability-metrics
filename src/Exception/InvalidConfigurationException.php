<?php

declare(strict_types=1);

namespace Stability\Exception;

class InvalidConfigurationException extends StabilityException
{
    public static function onMissingConfigFile(string $path): self
    {
        return new self("No configuration file found at \"$path\".");
    }

    public static function onMissingBasePath(): self
    {
        return new self('Configuration is missing "base_path" property.');
    }

    public static function onMissingModules(): self
    {
        return new self('Configuration has no "modules" to run against.');
    }

    public static function onMissingModule(): self
    {
        return new self('Module is missing "module" path property.');
    }
}
