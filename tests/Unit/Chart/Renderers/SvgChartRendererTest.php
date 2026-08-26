<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Chart\Renderers;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Chart\Renderers\SvgChartRenderer;
use Stability\Metric\Result;
use Stability\Metric\StabilityResult;
use Stability\Metric\StableDependencyMetric;
use Stability\Metric\ZoneType;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Metric\StabilityResultFactory;

class SvgChartRendererTest extends TestCase
{
    private SvgChartRenderer $renderer;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new SvgChartRenderer();
    }

    public function test_file_extension_is_svg(): void
    {
        $this->assertEquals('svg', $this->renderer->fileExtension());
    }

    public function test_given_result_then_renders_valid_svg_structure(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        $this->assertStringContainsString('<svg xmlns="http://www.w3.org/2000/svg"', $output);
        $this->assertStringContainsString('viewBox=', $output);
        $this->assertStringContainsString('</svg>', $output);
    }

    public function test_given_result_then_component_names_appear_in_output(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        $this->assertStringContainsString('Module1', $output);
        $this->assertStringContainsString('Module2', $output);
        $this->assertStringContainsString('Module3', $output);
    }

    public function test_given_result_then_tooltip_contains_metric_values(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        // Module1 has instability=0.50, abstractness=0.67
        $this->assertStringContainsString('<title>', $output);
        $this->assertStringContainsString('I=0.50', $output);
        $this->assertStringContainsString('A=0.67', $output);
        $this->assertStringContainsString('D=', $output);
    }

    public function test_given_result_then_main_sequence_line_is_dashed(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        $this->assertStringContainsString('stroke-dasharray="8,4"', $output);
    }

    public function test_given_result_then_zone_shading_polygons_are_present(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        $this->assertStringContainsString('<polygon', $output);
        $this->assertStringContainsString('fill="#f44336" fill-opacity', $output);
        $this->assertStringContainsString('fill="#ff9800" fill-opacity', $output);
    }

    public function test_given_zone_pain_then_dot_is_red(): void
    {
        $output = $this->renderer->render($this->makeResult(ZoneType::PAIN));

        $this->assertStringContainsString('fill="#f44336" stroke="#fff"', $output);
    }

    public function test_given_zone_usefulness_then_dot_is_blue(): void
    {
        $output = $this->renderer->render($this->makeResult(ZoneType::USEFULNESS));

        $this->assertStringContainsString('fill="#2196f3" stroke="#fff"', $output);
    }

    public function test_given_zone_perfect_then_dot_is_green(): void
    {
        $output = $this->renderer->render($this->makeResult(ZoneType::PERFECT));

        $this->assertStringContainsString('fill="#4caf50" stroke="#fff"', $output);
    }

    public function test_given_zone_uselessness_then_dot_is_orange(): void
    {
        $output = $this->renderer->render($this->makeResult(ZoneType::USELESSNESS));

        $this->assertStringContainsString('fill="#ff9800" stroke="#fff"', $output);
    }

    public function test_given_empty_result_then_no_component_dots_rendered(): void
    {
        $result = new StabilityResult([]);

        $output = $this->renderer->render($result);

        // Legend circles are always present; component dot circles have stroke="#fff"
        $this->assertStringNotContainsString('stroke="#fff"', $output);
        $this->assertStringContainsString('<svg', $output);
    }

    private function makeResult(ZoneType $zone): Result
    {
        return new StabilityResult(
            [new StableDependencyMetric(ComponentFactory::module1(), $zone, 0.5, 0.5, 0.0)],
        );
    }

    public function test_given_result_then_zone_shading_only_covers_the_threshold_corners(): void
    {
        $output = $this->renderer->render(StabilityResultFactory::testSource());

        // D >= 0.7 puts each zone in a corner: 30% of the plot on each axis, not half of it.
        $this->assertStringContainsString(
            '<polygon points="70,470 202,470 70,344" fill="#f44336"',
            $output,
        );
        $this->assertStringContainsString(
            '<polygon points="510,50 378,50 510,176" fill="#ff9800"',
            $output,
        );
    }
}
