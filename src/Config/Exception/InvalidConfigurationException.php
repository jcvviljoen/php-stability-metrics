<?php

declare(strict_types=1);

namespace Stability\Config\Exception;

use Stability\StabilityException;
use Throwable;

class InvalidConfigurationException extends StabilityException
{
    public static function onMissingConfigFile(string $path): self
    {
        return new self("No configuration file found at \"$path\".");
    }

    public static function onMissingModules(): self
    {
        return new self('Configuration has no "modules" to run against.');
    }

    public static function onMissingModuleName(): self
    {
        return new self("Module is missing the \"name\" property.");
    }

    public static function onDuplicateModuleName(string $moduleName): self
    {
        return new self("Duplicate module name found: \"$moduleName\".");
    }

    public static function onMissingModulePath(): self
    {
        return new self('Module is missing the "path" property.');
    }

    public static function onMissingOutputOption(): self
    {
        return new self('Output setting is missing the "option" property.');
    }

    public static function onUnreadableConfiguration(string $path, Throwable $cause): self
    {
        $reason = $cause->getMessage();
        $root = $cause;

        while (null !== $root->getPrevious()) {
            $root = $root->getPrevious();
        }

        if ($root !== $cause && '' !== $root->getMessage()) {
            $reason .= " ({$root->getMessage()})";
        }

        return new self("Configuration file \"$path\" could not be read: $reason", $cause);
    }
}
