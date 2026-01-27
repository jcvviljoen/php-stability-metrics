<?php

declare(strict_types=1);

namespace Stability\Config;

interface Module
{
    /**
     * The module's unique name.
     */
    public function name(): string;

    /**
     * Define the module path (from the project root) that should be analysed.
     */
    public function path(): string;

    /**
     * Define the threshold (between 0 and 1) for when warnings for this zone should be raised.
     */
    public function thresholdZoneOfPain(): float;

    /**
     * Define the threshold (between 0 and 1) for when warnings for this zone should be raised.
     */
    public function thresholdZoneOfUselessness(): float;

    /**
     * Define the components within the module to be excluded from the stability check.
     *
     * @return array<string> $exclude
     */
    public function exclude(): array;
}
