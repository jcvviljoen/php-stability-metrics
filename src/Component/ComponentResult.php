<?php

namespace Instability\Component;

readonly class ComponentResult
{
    public function __construct(
        public Component $component,
        public float $abstractness,
        public float $instability,
        public float $dms,
    ) {
    }
}
