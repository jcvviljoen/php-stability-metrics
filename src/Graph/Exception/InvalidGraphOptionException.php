<?php

declare(strict_types=1);

namespace Stability\Graph\Exception;

use Stability\Graph\GraphOption;
use Stability\Shared\StabilityException;

class InvalidGraphOptionException extends StabilityException
{
    public static function onUnsupportedRenderer(string $option): self
    {
        $available = implode(', ', array_map(
            fn(GraphOption $case) => "\"{$case->value}\"",
            GraphOption::cases(),
        ));

        return new self("Unsupported graph renderer \"$option\". Available renderers: $available.");
    }
}
