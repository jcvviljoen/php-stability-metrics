<?php

declare(strict_types=1);

namespace Stability\Chart\Exception;

use Stability\Chart\ChartOption;
use Stability\StabilityException;

class InvalidChartOptionException extends StabilityException
{
    public static function onUnsupportedRenderer(string $option): self
    {
        $available = implode(', ', array_map(
            fn(ChartOption $case) => "\"{$case->value}\"",
            ChartOption::cases(),
        ));

        return new self("Unsupported chart renderer \"$option\". Available renderers: $available.");
    }
}
