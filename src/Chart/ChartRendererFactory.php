<?php

declare(strict_types=1);

namespace Stability\Chart;

use Stability\Chart\Renderers\SvgChartRenderer;

readonly class ChartRendererFactory
{
    public static function create(ChartOption $option): ChartRenderer
    {
        return match ($option) {
            ChartOption::SVG => new SvgChartRenderer(),
        };
    }
}
