<?php

declare(strict_types=1);

namespace Stability\Metric;

/**
 * The metrics calculated for a single component.
 *
 * Values are reported at full precision. How many decimal places to show is a question
 * about presenting them, so it belongs to whoever is doing the presenting: rounding here
 * once had the chart plot every component to two decimal places.
 */
interface ComponentMetric
{
    /**
     * The name of the component these metrics were calculated for.
     *
     * Reporting is all anything downstream does with the component, so the name is all
     * a metric needs to carry. Holding the component itself would point the Metric
     * component back at Component and close a dependency cycle.
     */
    public string $componentName { get; }

    public ZoneType $zone { get; }

    /**
     * Abstractness (A), in the range [0, 1].
     */
    public function abstractness(): float;

    /**
     * Instability (I), in the range [0, 1].
     */
    public function instability(): float;

    /**
     * Distance from the Main Sequence (D), in the range [0, 1].
     */
    public function dms(): float;
}
