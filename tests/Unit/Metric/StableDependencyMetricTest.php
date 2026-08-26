<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Metric;

use PHPUnit\Framework\TestCase;
use Stability\Tests\_Fixtures\Metric\StableDependencyMetricFactory;

class StableDependencyMetricTest extends TestCase
{
    public function test_given_a_metric_then_it_reports_its_values_at_full_precision(): void
    {
        $metric = StableDependencyMetricFactory::module1();

        $this->assertSame(0.6666666666666666, $metric->abstractness());
        $this->assertSame(0.5, $metric->instability());
        $this->assertSame(0.16666666666666652, $metric->dms());
    }
}
