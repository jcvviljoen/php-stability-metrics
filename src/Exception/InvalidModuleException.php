<?php

namespace Instability\Exception;

class InvalidModuleException extends StabilityException
{
    public static function onInvalidModulePath(string $module): self
    {
        return new self("Module directory $module not found.");
    }

    public function output(): array
    {
        // TODO: Implement output().
    }
}
