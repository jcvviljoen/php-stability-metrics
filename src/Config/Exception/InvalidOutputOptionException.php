<?php

declare(strict_types=1);

namespace Stability\Config\Exception;

use Stability\Config\OutputOption;
use Stability\Shared\StabilityException;

class InvalidOutputOptionException extends StabilityException
{
    public static function onUnsupportedOption(string $option): self
    {
        $available = implode(', ', array_map(
            fn(OutputOption $case) => "\"{$case->value}\"",
            OutputOption::cases(),
        ));

        return new self("Unsupported output option \"$option\". Available options: $available.");
    }
}
