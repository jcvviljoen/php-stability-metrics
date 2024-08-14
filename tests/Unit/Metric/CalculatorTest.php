<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Metric;

use PHPUnit\Framework\TestCase;
use Stability\Metric\Calculator;
use Stability\Metric\ZoneType;

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
        $fanIn = 1;
        $fanOut = 2;

        $instability = Calculator::instability($fanIn, $fanOut);

        $this->assertEquals(0.6666666666666666, $instability);
    }

    public function test_given_fan_in_is_zero_when_calculating_instability_then_return_one(): void
    {
        $fanIn = 0;
        $fanOut = 2;

        $instability = Calculator::instability($fanIn, $fanOut);

        $this->assertEquals(1, $instability);
    }

    public function test_given_fan_out_is_zero_when_calculating_instability_then_return_zero(): void
    {
        $fanIn = 1;
        $fanOut = 0;

        $instability = Calculator::instability($fanIn, $fanOut);

        $this->assertEquals(0, $instability);
    }

    public function test_dms(): void
    {
        $instability = 0.6666666666666666;
        $abstractness = 0.6;

        $dms = Calculator::dms($instability, $abstractness);

        $this->assertEquals(0.2666666666666666, $dms);
    }

    public function test_given_dms_when_zero_then_zone_is_perfect(): void
    {
        $abstractness = 0.5;
        $instability = 0.5;
        $dms = 0;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::PERFECT, $zone);
    }

    public function test_given_dms_when_closer_to_zone_of_pain_then_return_zone_of_pain(): void
    {
        $abstractness = 0.6;
        $instability = 0.2;
        $dms = 0.2;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::PAIN, $zone);
    }

    public function test_given_zone_of_pain_when_threshold_not_exceeded_then_return_usefulness(): void
    {
        $abstractness = 0.6;
        $instability = 0.2;
        $dms = 0.2;
        $thresholdZoneOfPain = 0.3;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::USEFULNESS, $zone);
    }

    public function test_given_dms_when_closer_to_zone_of_uselessness_then_return_uselessness(): void
    {
        $abstractness = 0.6;
        $instability = 0.6;
        $dms = 0.2;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::USELESSNESS, $zone);
    }

    public function test_given_zone_of_uselessness_when_threshold_not_exceeded_then_return_usefulness(): void
    {
        $abstractness = 0.6;
        $instability = 0.6;
        $dms = 0.2;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.3;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::USEFULNESS, $zone);
    }

    public function test_given_high_dms_when_component_is_maximally_stable_then_return_pain(): void
    {
        $abstractness = 0;
        $instability = 0;
        $dms = 1;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::PAIN, $zone);
    }

    public function test_given_high_dms_when_component_is_maximally_abstract_then_return_uselessness(): void
    {
        $abstractness = 1;
        $instability = 1;
        $dms = 1;
        $thresholdZoneOfPain = 0.1;
        $thresholdZoneOfUselessness = 0.1;

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $thresholdZoneOfPain,
            $thresholdZoneOfUselessness,
        );

        $this->assertEquals(ZoneType::USELESSNESS, $zone);
    }
}
