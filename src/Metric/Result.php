<?php

declare(strict_types=1);

namespace Stability\Metric;

/**
 * The outcome of an analysis, which is what every writer and renderer reports on.
 *
 * @see StabilityResult The result produced by analysing stable dependency metrics.
 */
interface Result
{
    /**
     * @var array<ComponentMetric>
     */
    public array $stableDependencyMetrics { get; }
}
