<?php

declare(strict_types=1);

namespace Stability\Chart;

use Stability\Metric\Result;

interface ChartRenderer
{
    /**
     * Render the stability chart to a string.
     */
    public function render(Result $result): string;

    /**
     * File extension for the rendered output (without the leading dot).
     */
    public function fileExtension(): string;
}
