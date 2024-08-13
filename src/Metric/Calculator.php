<?php

declare(strict_types=1);

namespace Stability\Metric;

final readonly class Calculator
{
    /**
     * Abstractness metric (A) in the range [0, 1].
     *
     * A = 0 means the component has no abstract classes or interfaces.
     * A = 1 means the component is entirely made up of abstract classes and interfaces.
     *
     * This metric is merely the ratio of abstract classes and interfaces to the total number of classes.
     */
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

    /**
     * Instability metric (I) in the range [0, 1].
     *
     * When I = 0 ($fanIn > 0 && $fanOut = 0), the component is maximally stable.
     * This component is responsible and independent because it is only dependent upon by other components
     * (dependency arrows point towards the component).
     *
     * When I = 1 ($fanIn = 0 && $fanOut > 0), the component is maximally unstable.
     * This component is irresponsible and dependent because it only ever depends on other components
     * (dependency arrows point away from the component).
     */
    public static function instability(float $fanIn, float $fanOut): float
    {
        if ($fanIn === 0.0) {
            return 1;
        }

        if ($fanOut === 0.0) {
            return 0;
        }

        $total = $fanIn + $fanOut;

        return $fanOut / $total;
    }

    /**
     * Distance from Main Sequence metric (D) in the range [0, 1].
     *
     * D = 0 means the component is on the Main Sequence.
     * D = 1 means the component is as far away from the Main Sequence as possible.
     *
     * The Main Sequence is a line from the top left corner (0, 1) to the bottom right corner (1, 0).
     * Any component where D != 0 can be reexamined and restructured.
     *
     * You can even apply statistical analysis to the DMS values to further evaluate the system's design.
     * For example, you can use variance to establish "control limits" to identify "exceptional" components.
     * You then put rules in place that (for example) you should reevaluate components that fall outside of
     * the first standard deviation.
     */
    public static function dms(float $instability, float $abstractness): float
    {
        return abs($instability + $abstractness - 1);
    }

    public static function zone(
        float $dms,
        float $thresholdZoneOfPain,
        float $thresholdZoneOfUselessness,
    ): ZoneType {
        if ($dms <= $thresholdZoneOfPain) {
            return ZoneType::PAIN;
        }

        if ($dms >= $thresholdZoneOfUselessness) {
            return ZoneType::USELESSNESS;
        }

        return ZoneType::USEFULNESS;
    }
}
