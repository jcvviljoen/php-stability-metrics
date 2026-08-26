<?php

declare(strict_types=1);

namespace Stability\Metric;

/**
 * The metrics calculated for a single component.
 *
 * Values are exposed formatted for display, because reporting them is all
 * the writers and renderers do with them.
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
    public function abstractness(): string;

    /**
     * Instability (I), in the range [0, 1].
     */
    public function instability(): string;

    /**
     * Distance from the Main Sequence (D), in the range [0, 1].
     */
    public function dms(): string;
}
