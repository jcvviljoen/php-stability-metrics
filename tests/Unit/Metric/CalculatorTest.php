<?php

namespace Instability\Tests\Unit\Metric;

use Instability\Metric\Calculator;
use PHPUnit\Framework\TestCase;

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

    public function test_instability(): void
    {
        $externalDependencies = 2;
        $internalDependencies = 1;

        $instability = Calculator::instability($externalDependencies, $internalDependencies);

        $this->assertEquals(0.6666666666666666, $instability);
    }

    public function test_dms(): void
    {
        $instability = 0.6666666666666666;
        $abstractness = 0.6;

        $dms = Calculator::dms($instability, $abstractness);

        $this->assertEquals(0.2666666666666666, $dms);
    }
}
