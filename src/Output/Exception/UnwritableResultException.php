<?php

declare(strict_types=1);

namespace Stability\Output\Exception;

use Stability\StabilityException;

/**
 * The Application component owns an exception for the files it writes itself. Sharing one
 * would mean Output depending on Application, which already depends on Output, so each
 * component owns the failure it can raise.
 */
class UnwritableResultException extends StabilityException
{
    public static function onFailedEncoding(): self
    {
        return new self('The analysis results could not be encoded to JSON.');
    }

    public static function onFailedWrite(string $path): self
    {
        return new self("Could not write the analysis results to \"$path\". "
            . 'Check that the directory exists and is writable.');
    }
}
