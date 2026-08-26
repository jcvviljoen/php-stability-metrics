<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;

/**
 * The points at which a component's distance from the main sequence is worth reporting
 * as a problem rather than as noise.
 *
 * Both values are a distance in the range [0, 1], the same range as the DMS they are
 * compared against. A threshold outside that range would fire for every component or
 * for none of them, so it is rejected here rather than quietly ignored.
 */
readonly class Thresholds
{
    /**
     * @throws InvalidComponentException
     */
    public function __construct(
        public float $zoneOfPain,
        public float $zoneOfUselessness,
    ) {
        $this->guardIsADistance('thresholdZoneOfPain', $zoneOfPain);
        $this->guardIsADistance('thresholdZoneOfUselessness', $zoneOfUselessness);
    }

    /**
     * @throws InvalidComponentException
     */
    private function guardIsADistance(string $threshold, float $value): void
    {
        if ($value < 0 || $value > 1) {
            throw InvalidComponentException::onThresholdOutOfRange($threshold, $value);
        }
    }
}
