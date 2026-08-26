<?php

declare(strict_types=1);

namespace Stability\Metric;

use JsonSerializable;
use Override;

readonly class StableDependencyMetric implements ComponentMetric, JsonSerializable
{
    public function __construct(
        public string $componentName,
        public ZoneType $zone,
        private float $abstractness,
        private float $instability,
        private float $dms,
    ) {
    }

    #[Override] public function abstractness(): float
    {
        return $this->abstractness;
    }

    #[Override] public function instability(): float
    {
        return $this->instability;
    }

    #[Override] public function dms(): float
    {
        return $this->dms;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override] public function jsonSerialize(): array
    {
        return [
            'component' => $this->componentName,
            'zone' => $this->zone,
            'abstractness' => $this->abstractness,
            'instability' => $this->instability,
            'dms' => $this->dms,
        ];
    }
}
