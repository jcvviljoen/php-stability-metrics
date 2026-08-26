<?php

declare(strict_types=1);

namespace Stability\Metric;

use Stability\Component\Component;

/**
 * The metrics calculated for a single component.
 *
 * Values are exposed formatted for display, because reporting them is all
 * the writers and renderers do with them.
 */
interface ComponentMetric
{
    public Component $component { get; }

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
