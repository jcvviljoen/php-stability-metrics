<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Chart;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Chart\ChartOption;
use Stability\Chart\ChartRendererFactory;
use Stability\Chart\Renderers\SvgChartRenderer;

class ChartRendererFactoryTest extends TestCase
{
    /**
     * @param class-string $expected
     */
    #[DataProvider('provide_chart_options')]
    public function test_create_chart_renderer(ChartOption $option, string $expected): void
    {
        $renderer = ChartRendererFactory::create($option);

        $this->assertInstanceOf($expected, $renderer);
    }

    /**
     * @return array<string, array{option: ChartOption, expected: class-string}>
     */
    public static function provide_chart_options(): array
    {
        return [
            'When given an "svg" option, then provide the SVG renderer' => [
                'option' => ChartOption::SVG,
                'expected' => SvgChartRenderer::class,
            ],
        ];
    }
}
