<?php

declare(strict_types=1);

namespace Stability\Component\Exception;

use Stability\Shared\StabilityException;

class InvalidComponentException extends StabilityException
{
    public static function onInvalidModulePath(string $path): self
    {
        return new self("Component's module directory \"$path\" could not be found.");
    }

    public static function onThresholdOutOfRange(string $threshold, float $value): self
    {
        return new self("Threshold \"$threshold\" must be a distance between 0 and 1, but was $value.");
    }

    public static function onMismatchedNamespace(string $namespace, string $primaryNamespace): self
    {
        return new self("Namespace \"$namespace\" does not sit under the component's"
            . " primary namespace \"$primaryNamespace\".");
    }

    public static function onEmptyComponent(string $moduleName): self
    {
        return new self("Component for module \"$moduleName\" contains no PHP files.");
    }
}
