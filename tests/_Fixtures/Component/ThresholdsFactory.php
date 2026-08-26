<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\Thresholds;

readonly class ThresholdsFactory
{
    public static function default(): Thresholds
    {
        return new Thresholds(0.7, 0.7);
    }
}
