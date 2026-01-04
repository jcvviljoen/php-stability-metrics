<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Metric;

use PHPUnit\Framework\TestCase;
use Stability\Tests\_Fixtures\Metric\StableDependencyMetricFactory;

class StableDependencyMetricTest extends TestCase
{
    public function test_given_a_metric_then_its_float_values_can_be_formatted_to_string_with_2_decimal_points(): void
    {
        $metric = StableDependencyMetricFactory::module1();

        $this->assertSame('0.67', $metric->abstractness());
        $this->assertSame('0.50', $metric->instability());
        $this->assertSame('0.17', $metric->dms());
    }
}
