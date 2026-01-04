<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures;

use Stability\Metric\Result;
use Stability\Tests\_Fixtures\Metric\StableDependencyMetricFactory;

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
