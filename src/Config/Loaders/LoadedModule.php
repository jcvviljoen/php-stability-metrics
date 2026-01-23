<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Module;

readonly class LoadedModule implements Module
{
    /**
     * @param list<string> $exclude
     */
    public function __construct(
        private string $name,
        private string $path,
        private float $thresholdZoneOfPain = 0.7,
        private float $thresholdZoneOfUselessness = 0.7,
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
