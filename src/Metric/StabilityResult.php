<?php

declare(strict_types=1);

namespace Stability\Metric;

use Override;

readonly class StabilityResult implements Result
{
    /**
     * @param array<ComponentMetric> $stableDependencyMetrics
     */
    public function __construct(
        #[Override] public array $stableDependencyMetrics,
    ) {
    }
}
