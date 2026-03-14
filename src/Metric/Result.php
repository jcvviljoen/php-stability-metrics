<?php

declare(strict_types=1);

namespace Stability\Metric;

use JsonSerializable;
use Override;
use Stability\Component\DependencyMap;

readonly class Result implements JsonSerializable
{
    /**
     * @param array<StableDependencyMetric> $stableDependencyMetrics
     */
    public function __construct(
        public array $stableDependencyMetrics,
        public DependencyMap $dependencyMap,
    ) {
    }

    /** @return array<string, mixed> */
    #[Override] public function jsonSerialize(): array
    {
        return ['stableDependencyMetrics' => $this->stableDependencyMetrics];
    }
}
