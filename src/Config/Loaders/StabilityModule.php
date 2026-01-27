<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Module;

readonly class StabilityModule implements Module
{
    public const float DEFAULT_THRESHOLD_ZONE_OF_PAIN = 0.7;
    public const float DEFAULT_THRESHOLD_ZONE_OF_USELESSNESS = 0.7;

    /**
     * @param list<string> $exclude
     */
    public function __construct(
        private string $name,
        private string $path,
        private float $thresholdZoneOfPain = self::DEFAULT_THRESHOLD_ZONE_OF_PAIN,
        private float $thresholdZoneOfUselessness = self::DEFAULT_THRESHOLD_ZONE_OF_USELESSNESS,
        private array $exclude = [],
    ) {
    }

    #[Override] public function name(): string
    {
        return $this->name;
    }

    #[Override] public function path(): string
    {
        return $this->path;
    }

    #[Override] public function thresholdZoneOfPain(): float
    {
        return $this->thresholdZoneOfPain;
    }

    #[Override] public function thresholdZoneOfUselessness(): float
    {
        return $this->thresholdZoneOfUselessness;
    }

        /**
        * @return list<string>
        */
    #[Override] public function exclude(): array
    {
        return $this->exclude;
    }
}
