<?php

declare(strict_types=1);

namespace Stability\Config;

interface Module
{
    public function name(): string;

    public function thresholdZoneOfPain(): float;

    public function thresholdZoneOfUselessness(): float;

    /**
     * @return array<string> $exclude
     */
    public function exclude(): array;
}
