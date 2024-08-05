<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Metric\ZoneType;

readonly class ComponentResult
{
    public function __construct(
        public Component $component,
        public float $abstractness,
        public float $instability,
        public float $dms,
        public ZoneType $zone,
    ) {
    }
}
