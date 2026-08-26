<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Metric;

use Stability\Metric\Result;
use Stability\Metric\StabilityResult;

class StabilityResultFactory
{
    public static function testSource(): Result
    {
        return new StabilityResult([
            StableDependencyMetricFactory::module1(),
            StableDependencyMetricFactory::module2(),
            StableDependencyMetricFactory::module3(),
        ]);
    }
}
