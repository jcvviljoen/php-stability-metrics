<?php

namespace Stability\Metric;

final readonly class Calculator
{
    public static function abstractness(
        int $abstractClassCount,
        int $interfaceCount,
        int $totalClassCount,
    ): float {
        if ($totalClassCount === 0) {
            return 0;
        }

        return ($abstractClassCount + $interfaceCount) / $totalClassCount;
    }

    public static function instability(float $fanIn, float $fanOut): float
    {
        $total = $fanIn + $fanOut;

        if ($total === 0.0) {
            return 0;
        }

        return $fanOut / ($total);
    }

    /**
     * Distance from Main Sequence metric.
     */
    public static function dms(float $instability, float $abstractness): float
    {
        return abs($instability + $abstractness - 1);
    }
}
