<?php

declare(strict_types=1);

namespace Stability;

use Stability\Component\ComponentResult;

readonly class StabilityResult
{
    /**
     * @param array<ComponentResult> $componentResults
     */
    public function __construct(
        public array $componentResults,
    ) {
    }
}
