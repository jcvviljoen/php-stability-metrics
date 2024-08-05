<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Config\Module\Module;

readonly class Config
{
    /**
     * @param array<string> $default
     * @param array<Module> $modules
     */
    public function __construct(
        public string $basePath,
        public float $thresholdZoneOfPain,
        public float $thresholdZoneOfUselessness,
        public array $default,
        public array $modules,
    ) {
    }
}
