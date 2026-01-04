<?php

declare(strict_types=1);

namespace Stability\Component\File\Exception;

use Stability\StabilityException;

class InvalidMetadataException extends StabilityException
{
    public static function onMissingNamespace(): self
    {
        return new self('Metadata is missing a namespace declaration.');
    }
}
