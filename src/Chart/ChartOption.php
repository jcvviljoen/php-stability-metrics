<?php

declare(strict_types=1);

namespace Stability\Chart;

use Stability\Chart\Exception\InvalidChartOptionException;

enum ChartOption: string
{
    case SVG = 'svg';

    /**
     * Resolves the value given on the command line.
     *
     * @throws InvalidChartOptionException
     */
    public static function fromArgument(string $option): self
    {
        return self::tryFrom($option)
            ?? throw InvalidChartOptionException::onUnsupportedRenderer($option);
    }
}
