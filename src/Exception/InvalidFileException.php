<?php

declare(strict_types=1);

namespace Stability\Exception;

use Stability\Component\Class\ClassType;

class InvalidFileException extends StabilityException
{
    public static function onInvalidFileType(string $file): self
    {
        return new self(
            "The file type of file \"$file\" could not be determined. Perhaps it should be excluded?",
        );
    }

    public static function onUnsupportedFileType(string $file, ClassType $type): self
    {
        return new self(
            "Unsupported file type \"$type->name\" found for file \"$file\".",
        );
    }
}
