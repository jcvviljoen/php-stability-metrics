<?php

namespace Stability\Tests\Unit\Metric;

use PHPUnit\Framework\TestCase;
use Stability\Metric\Calculator;

class CalculatorTest extends TestCase
{
    public function test_abstractness(): void
    {
        $abstractClassCount = 2;
        $interfaceCount = 1;
        $totalClassCount = 5;

        $abstractness = Calculator::abstractness($abstractClassCount, $interfaceCount, $totalClassCount);

        $this->assertEquals(0.6, $abstractness);
    }

    public function test_given_total_class_count_is_zero_when_calculating_abstractness_then_return_zero(): void
    {
        $abstractClassCount = 0;
        $interfaceCount = 0;
        $totalClassCount = 0;

        $abstractness = Calculator::abstractness($abstractClassCount, $interfaceCount, $totalClassCount);

        $this->assertEquals(0, $abstractness);
    }

    public function test_instability(): void
    {
        $externalDependencies = 2;
        $internalDependencies = 1;

        $instability = Calculator::instability($externalDependencies, $internalDependencies);

        $this->assertEquals(0.6666666666666666, $instability);
    }

    public function test_given_total_dependencies_is_zero_when_calculating_instability_then_return_zero(): void
    {
        $externalDependencies = 0;
        $internalDependencies = 0;

        $instability = Calculator::instability($externalDependencies, $internalDependencies);

        $this->assertEquals(0, $instability);
    }

    public function test_dms(): void
    {
        $instability = 0.6666666666666666;
        $abstractness = 0.6;

        $dms = Calculator::dms($instability, $abstractness);

        $this->assertEquals(0.2666666666666666, $dms);
    }
}
