<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Metric;

use Stability\Metric\StableDependencyMetric;
use Stability\Metric\ZoneType;
use Stability\Tests\_Fixtures\Component\ComponentFactory;

class StableDependencyMetricFactory
{
    public static function module1(): StableDependencyMetric
    {
        return new StableDependencyMetric(
            ComponentFactory::module1(),
            ZoneType::USEFULNESS,
            0.6666666666666666,
            0.5,
            0.16666666666666652,
        );
    }

    public static function module2(): StableDependencyMetric
    {
        return new StableDependencyMetric(
            ComponentFactory::module2(),
            ZoneType::USEFULNESS,
            0.5,
            1,
            0.5,
        );
    }

    public static function module3(): StableDependencyMetric
    {
        return new StableDependencyMetric(
            ComponentFactory::module3(),
            ZoneType::PAIN,
            0,
            0,
            1,
        );
    }
}
