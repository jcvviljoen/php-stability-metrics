<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Stability\Config\Module;

readonly class LoadedModule implements Module
{
    /**
     * @param list<string> $exclude
     */
    public function __construct(
        private string $name,
        private float $thresholdZoneOfPain,
        private float $thresholdZoneOfUselessness,
        private array $exclude,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function thresholdZoneOfPain(): float
    {
        return $this->thresholdZoneOfPain;
    }

    public function thresholdZoneOfUselessness(): float
    {
        return $this->thresholdZoneOfUselessness;
    }

        /**
        * @return list<string>
        */
    public function exclude(): array
    {
        return $this->exclude;
    }
}
