<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Metric;

use Stability\Component\ComponentCollection;
use Stability\Component\DependencyMap;
use Stability\Metric\Result;
use Stability\Tests\_Fixtures\Component\ComponentFactory;

class StabilityResultFactory
{
    public static function testSource(): Result
    {
        $components = new ComponentCollection([
            ComponentFactory::module1(),
            ComponentFactory::module2(),
            ComponentFactory::module3(),
        ]);

        return new Result(
            [
                StableDependencyMetricFactory::module1(),
                StableDependencyMetricFactory::module2(),
                StableDependencyMetricFactory::module3(),
            ],
            DependencyMap::from($components),
        );
    }
}
