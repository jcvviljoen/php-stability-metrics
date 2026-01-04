<?php

declare(strict_types=1);

namespace Stability\Component\File\Exception;

use Stability\StabilityException;

class InvalidFileException extends StabilityException
{
    public static function onInvalidFileType(string $file): self
    {
        return new self(
            "The file type of file \"$file\" could not be determined. Perhaps it should be excluded?",
        );
    }
}
