<?php

declare(strict_types=1);

namespace Stability\Metric;

readonly class Result
{
    /**
     * @param array<StableDependencyMetric> $stableDependencyMetrics
     */
    public function __construct(
        public array $stableDependencyMetrics,
    ) {
    }
}
