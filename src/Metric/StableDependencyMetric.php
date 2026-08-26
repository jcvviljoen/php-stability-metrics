<?php

declare(strict_types=1);

namespace Stability\Metric;

use JsonSerializable;
use Override;

readonly class StableDependencyMetric implements ComponentMetric, JsonSerializable
{
    private const int FORMAT_PRECISION = 2;

    public function __construct(
        public string $componentName,
        public ZoneType $zone,
        private float $abstractness,
        private float $instability,
        private float $dms,
    ) {
    }

    #[Override] public function abstractness(): string
    {
        return $this->formatFloat($this->abstractness);
    }

    #[Override] public function instability(): string
    {
        return $this->formatFloat($this->instability);
    }

    #[Override] public function dms(): string
    {
        return $this->formatFloat($this->dms);
    }

    private function formatFloat(float $value): string
    {
        return number_format($value, self::FORMAT_PRECISION);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override] public function jsonSerialize(): array
    {
        return [
            'component' => $this->componentName,
            'zone' => $this->zone,
            'abstractness' => $this->abstractness(),
            'instability' => $this->instability(),
            'dms' => $this->dms(),
        ];
    }
}
