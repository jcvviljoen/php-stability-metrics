<?php

declare(strict_types=1);

namespace Stability\Application\Exception;

use Stability\Shared\StabilityException;

class UnwritableFileException extends StabilityException
{
    public static function onFailedWrite(string $path): self
    {
        return new self("Could not write to \"$path\". Check that the directory exists and is writable.");
    }
}
