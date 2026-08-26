<?php

declare(strict_types=1);

namespace Stability\Metric;

readonly class StabilityResult implements Result
{
    /**
     * @param array<ComponentMetric> $stableDependencyMetrics
     */
    public function __construct(
        public array $stableDependencyMetrics,
    ) {
    }
}
