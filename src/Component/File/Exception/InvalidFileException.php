<?php

declare(strict_types=1);

namespace Stability\Component\File\Exception;

use Stability\Shared\StabilityException;

class InvalidFileException extends StabilityException
{
    public static function onUnreadableFile(string $file): self
    {
        return new self("The file \"$file\" could not be opened for reading.");
    }
}
