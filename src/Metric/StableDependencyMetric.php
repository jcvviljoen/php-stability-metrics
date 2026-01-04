<?php

declare(strict_types=1);

namespace Stability\Metric;

use JsonSerializable;
use Override;
use Stability\Component\Component;

readonly class StableDependencyMetric implements JsonSerializable
{
    private const int FORMAT_PRECISION = 2;

    public function __construct(
        public Component $component,
        public ZoneType $zone,
        private float $abstractness,
        private float $instability,
        private float $dms,
    ) {
    }

    public function abstractness(): string
    {
        return $this->formatFloat($this->abstractness);
    }

    public function instability(): string
    {
        return $this->formatFloat($this->instability);
    }

    public function dms(): string
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
            'component' => $this->component->name(),
            'zone' => $this->zone,
            'abstractness' => $this->abstractness(),
            'instability' => $this->instability(),
            'dms' => $this->dms(),
        ];
    }
}
