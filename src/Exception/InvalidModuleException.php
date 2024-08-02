<?php

declare(strict_types=1);

namespace Stability\Exception;

class InvalidModuleException extends StabilityException
{
    public static function onInvalidModulePath(string $path): self
    {
        return new self("Module directory \"$path\" not found.");
    }
}
