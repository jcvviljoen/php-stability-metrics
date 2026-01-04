<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Metric;

use Stability\Metric\Result;

class StabilityResultFactory
{
    public static function testSource(): Result
    {
        return new Result([
            StableDependencyMetricFactory::module1(),
            StableDependencyMetricFactory::module2(),
            StableDependencyMetricFactory::module3(),
        ]);
    }
}
