<?php

namespace Instability\Metric;

readonly class Calculator
{
    public static function abstractness(
        int $abstractClassCount,
        int $interfaceCount,
        int $totalClassCount
    ): float {
        return ($abstractClassCount + $interfaceCount) / $totalClassCount;
    }

    public static function instability(float $externalDependencies, float $internalDependencies): float
    {
        return $externalDependencies / ($externalDependencies + $internalDependencies);
    }

    /**
     * Distance from Main Sequence metric.
     */
    public static function dms(float $instability, float $abstractness): float
    {
        return abs($instability + $abstractness - 1);
    }
}
