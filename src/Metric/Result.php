<?php

declare(strict_types=1);

namespace Stability\Metric;

readonly class Result
{
    /**
     * @param array<StableDependencyMetric> $componentResults
     */
    public function __construct(
        public array $componentResults,
    ) {
    }
}
