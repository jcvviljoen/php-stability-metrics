<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Chart;

use PHPUnit\Framework\TestCase;
use Stability\Chart\ChartOption;
use Stability\Chart\Exception\InvalidChartOptionException;

class ChartOptionTest extends TestCase
{
    public function test_given_a_supported_renderer_then_the_option_is_resolved(): void
    {
        $this->assertEquals(ChartOption::SVG, ChartOption::fromArgument('svg'));
    }

    public function test_given_an_unsupported_renderer_then_throws_with_the_available_renderers(): void
    {
        $this->expectException(InvalidChartOptionException::class);
        $this->expectExceptionMessage('Unsupported chart renderer "mermaid". Available renderers: "svg".');

        ChartOption::fromArgument('mermaid');
    }
}
