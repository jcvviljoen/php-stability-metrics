<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\Thresholds;
use Stability\Tests\ExpectThrows;

class ThresholdsTest extends TestCase
{
    use ExpectThrows;

    public function test_given_distances_in_range_then_they_are_kept(): void
    {
        $thresholds = new Thresholds(0.2, 0.8);

        $this->assertSame(0.2, $thresholds->zoneOfPain);
        $this->assertSame(0.8, $thresholds->zoneOfUselessness);
    }

    public function test_given_the_bounds_of_the_range_then_they_are_accepted(): void
    {
        $thresholds = new Thresholds(0.0, 1.0);

        $this->assertSame(0.0, $thresholds->zoneOfPain);
        $this->assertSame(1.0, $thresholds->zoneOfUselessness);
    }

    #[DataProvider('provideThresholdsOutOfRange')]
    public function test_given_a_distance_out_of_range_then_throw_exception(
        float $zoneOfPain,
        float $zoneOfUselessness,
        string $expectedThreshold,
        float $expectedValue,
    ): void {
        $exception = $this->expectThrows(fn() => new Thresholds($zoneOfPain, $zoneOfUselessness));

        $this->assertEquals(
            InvalidComponentException::onThresholdOutOfRange($expectedThreshold, $expectedValue),
            $exception,
        );
    }

    /**
     * @return array<string, array{
     *     zoneOfPain: float,
     *     zoneOfUselessness: float,
     *     expectedThreshold: string,
     *     expectedValue: float
     * }>
     */
    public static function provideThresholdsOutOfRange(): array
    {
        return [
            'When the zone of pain threshold is above the range, then' => [
                'zoneOfPain' => 1.5,
                'zoneOfUselessness' => 0.7,
                'expectedThreshold' => 'thresholdZoneOfPain',
                'expectedValue' => 1.5,
            ],
            'When the zone of pain threshold is below the range, then' => [
                'zoneOfPain' => -0.1,
                'zoneOfUselessness' => 0.7,
                'expectedThreshold' => 'thresholdZoneOfPain',
                'expectedValue' => -0.1,
            ],
            'When the zone of uselessness threshold is above the range, then' => [
                'zoneOfPain' => 0.7,
                'zoneOfUselessness' => 2.0,
                'expectedThreshold' => 'thresholdZoneOfUselessness',
                'expectedValue' => 2.0,
            ],
        ];
    }
}
