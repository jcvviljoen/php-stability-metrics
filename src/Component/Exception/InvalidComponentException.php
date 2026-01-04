<?php

declare(strict_types=1);

namespace Stability\Component\Exception;

use Stability\StabilityException;

class InvalidComponentException extends StabilityException
{
    public static function onInvalidModulePath(string $path): self
    {
        return new self("Component's module directory \"$path\" could not be found.");
    }

    public static function onEmptyComponent(string $moduleName): self
    {
        return new self("Component for module \"$moduleName\" contains no PHP files.");
    }
}
