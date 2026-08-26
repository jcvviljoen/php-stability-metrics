<?php

declare(strict_types=1);

namespace Stability\Chart\Renderers;

use Override;
use Stability\Chart\ChartRenderer;
use Stability\Metric\ComponentMetric;
use Stability\Metric\Result;
use Stability\Metric\ZoneType;

/**
 * Renders an SVG scatter plot of component stability metrics against the DMS main sequence.
 *
 * Zone shading marks where a component would be classified as painful or useless at the
 * default threshold. Thresholds are configurable per component, and one chart can only
 * draw one boundary, so read the dot colours for the classification each component
 * actually received.
 *
 * Each component is plotted at (Instability, Abstractness) and colour-coded by zone:
 *   - Green = perfectly balanced (on or near the main sequence)
 *   - Blue = useful (stable and abstract)
 *   - Red = Zone of Pain (stable but concrete)
 *   - Orange = Zone of Uselessness (instable and abstract)
 *
 * Hover over a dot to see its name, I, A, D values, and zone classification.
 */
readonly class SvgChartRenderer implements ChartRenderer
{
    private const int SVG_WIDTH = 660;
    private const int SVG_HEIGHT = 580;
    private const int PLOT_LEFT = 70;
    private const int PLOT_TOP = 50;
    private const int PLOT_RIGHT = 510;
    private const int PLOT_BOTTOM = 470;
    private const int PLOT_WIDTH = 440; // PLOT_RIGHT - PLOT_LEFT
    private const int PLOT_HEIGHT = 420; // PLOT_BOTTOM - PLOT_TOP
    private const float ZONE_THRESHOLD = 0.7;

    // Only the hover text is rounded. Dots are placed at full precision.
    private const int TOOLTIP_PRECISION = 2;

    #[Override] public function render(Result $result): string
    {
        $lines = [];
        $lines[] = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d">',
            self::SVG_WIDTH,
            self::SVG_HEIGHT,
            self::SVG_WIDTH,
            self::SVG_HEIGHT,
        );
        $lines[] = '<rect width="100%" height="100%" fill="#ffffff"/>';
        $lines[] = sprintf(
            '<text x="%d" y="30" text-anchor="middle" font-family="sans-serif"'
            . ' font-size="16" font-weight="bold" fill="#333">Stability Metrics against the Main'
            . ' Sequence</text>',
            intdiv(self::SVG_WIDTH, 2),
        );
        $lines[] = $this->renderZoneShading();
        $lines[] = $this->renderGrid();
        $lines[] = sprintf(
            '<rect x="%d" y="%d" width="%d" height="%d" fill="none" stroke="#aaa" stroke-width="1"/>',
            self::PLOT_LEFT,
            self::PLOT_TOP,
            self::PLOT_WIDTH,
            self::PLOT_HEIGHT,
        );
        $lines[] = sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="#555" stroke-width="2" stroke-dasharray="8,4"/>',
            self::PLOT_LEFT,
            self::PLOT_TOP,
            self::PLOT_RIGHT,
            self::PLOT_BOTTOM,
        );
        $lines[] = $this->renderAxisLabels();

        foreach ($result->stableDependencyMetrics as $metric) {
            $lines[] = $this->renderDot($metric);
        }

        $lines[] = $this->renderLegend();
        $lines[] = '</svg>';

        return implode("\n", $lines) . "\n";
    }

    #[Override] public function fileExtension(): string
    {
        return 'svg';
    }

    private function renderZoneShading(): string
    {
        $l = self::PLOT_LEFT;
        $t = self::PLOT_TOP;
        $r = self::PLOT_RIGHT;
        $b = self::PLOT_BOTTOM;

        // A component is painful or useless once its distance from the main sequence
        // reaches the threshold, which puts both zones in a corner rather than over a
        // whole half of the plot: D >= 0.7 means A + I <= 0.3 or A + I >= 1.7.
        $span = 1.0 - self::ZONE_THRESHOLD;
        $width = (int) round($span * self::PLOT_WIDTH);
        $height = (int) round($span * self::PLOT_HEIGHT);

        // Zone of Pain: the corner nearest I=0, A=0.
        $pain = sprintf(
            '<polygon points="%d,%d %d,%d %d,%d" fill="#f44336" fill-opacity="0.08"/>',
            $l,
            $b,
            $l + $width,
            $b,
            $l,
            $b - $height,
        );

        // Zone of Uselessness: the corner nearest I=1, A=1.
        $useless = sprintf(
            '<polygon points="%d,%d %d,%d %d,%d" fill="#ff9800" fill-opacity="0.08"/>',
            $r,
            $t,
            $r - $width,
            $t,
            $r,
            $t + $height,
        );

        return $pain . "\n" . $useless;
    }

    private function renderGrid(): string
    {
        $lines = [];

        foreach ([0.25, 0.5, 0.75] as $v) {
            $x = self::PLOT_LEFT + (int) round($v * self::PLOT_WIDTH);
            $lines[] = sprintf(
                '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="#ddd" stroke-width="1"/>',
                $x,
                self::PLOT_TOP,
                $x,
                self::PLOT_BOTTOM,
            );

            $y = self::PLOT_TOP + (int) round($v * self::PLOT_HEIGHT);
            $lines[] = sprintf(
                '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="#ddd" stroke-width="1"/>',
                self::PLOT_LEFT,
                $y,
                self::PLOT_RIGHT,
                $y,
            );
        }

        return implode("\n", $lines);
    }

    private function renderAxisLabels(): string
    {
        $lines = [];
        $font = 'font-family="sans-serif" font-size="11" fill="#555"';

        // X axis ticks (Instability: 0 → 1, left to right)
        foreach ([0.0, 0.25, 0.5, 0.75, 1.0] as $v) {
            $x = self::PLOT_LEFT + (int) round($v * self::PLOT_WIDTH);
            $lines[] = sprintf(
                '<text x="%d" y="%d" text-anchor="middle" %s>%s</text>',
                $x,
                self::PLOT_BOTTOM + 16,
                $font,
                number_format($v, 2),
            );
        }

        // X axis title
        $xMid = self::PLOT_LEFT + intdiv(self::PLOT_WIDTH, 2);
        $lines[] = sprintf(
            '<text x="%d" y="%d" text-anchor="middle" font-family="sans-serif"'
            . ' font-size="13" font-weight="bold" fill="#444">Instability (I)</text>',
            $xMid,
            self::PLOT_BOTTOM + 36,
        );

        // Y axis ticks (Abstractness: 0 at bottom, 1 at top — flipped in SVG coords)
        foreach ([0.0, 0.25, 0.5, 0.75, 1.0] as $v) {
            $y = self::PLOT_TOP + (int) round((1.0 - $v) * self::PLOT_HEIGHT);
            $lines[] = sprintf(
                '<text x="%d" y="%d" text-anchor="end" dominant-baseline="middle" %s>%s</text>',
                self::PLOT_LEFT - 6,
                $y,
                $font,
                number_format($v, 2),
            );
        }

        // Y axis title (rotated 90° counter-clockwise)
        $yMid = self::PLOT_TOP + intdiv(self::PLOT_HEIGHT, 2);
        $lines[] = sprintf(
            '<text transform="rotate(-90,%d,%d)" x="%d" y="%d" text-anchor="middle"'
            . ' font-family="sans-serif" font-size="13" font-weight="bold" fill="#444">Abstractness (A)</text>',
            20,
            $yMid,
            20,
            $yMid,
        );

        // Zone labels
        $lines[] = sprintf(
            '<text x="%d" y="%d" font-family="sans-serif" font-size="10"'
            . ' fill="#f44336" fill-opacity="0.7">Zone of Pain</text>',
            self::PLOT_LEFT + 6,
            self::PLOT_BOTTOM - 8,
        );
        $lines[] = sprintf(
            '<text x="%d" y="%d" text-anchor="end" font-family="sans-serif" font-size="10"'
            . ' fill="#ff9800" fill-opacity="0.7">Zone of Uselessness</text>',
            self::PLOT_RIGHT - 6,
            self::PLOT_TOP + 14,
        );

        // Main sequence label — rotated to follow the dashed line
        $lines[] = sprintf(
            '<text x="%d" y="%d" font-family="sans-serif" font-size="10" fill="#555"'
            . ' transform="rotate(-45,%d,%d)">Main Sequence</text>',
            self::PLOT_LEFT + 20,
            self::PLOT_TOP + 20,
            self::PLOT_LEFT + 20,
            self::PLOT_TOP + 20,
        );

        return implode("\n", $lines);
    }

    private function renderDot(ComponentMetric $metric): string
    {
        $instability = $metric->instability();
        $abstractness = $metric->abstractness();
        $name = htmlspecialchars($metric->componentName, ENT_XML1);
        $zone = htmlspecialchars($metric->zone->description(), ENT_XML1);

        $cx = self::PLOT_LEFT + (int) round($instability * self::PLOT_WIDTH);
        $cy = self::PLOT_TOP + (int) round((1.0 - $abstractness) * self::PLOT_HEIGHT);
        $color = $this->zoneColor($metric->zone);

        $values = sprintf(
            'I=%s A=%s D=%s',
            number_format($instability, self::TOOLTIP_PRECISION),
            number_format($abstractness, self::TOOLTIP_PRECISION),
            number_format($metric->dms(), self::TOOLTIP_PRECISION),
        );
        $tooltip = htmlspecialchars("{$name} | {$values} | {$zone}", ENT_XML1);

        $lines = [];
        $lines[] = '<g>';
        $lines[] = sprintf(
            '<circle cx="%d" cy="%d" r="6" fill="%s" stroke="#fff" stroke-width="1.5">'
            . '<title>%s</title></circle>',
            $cx,
            $cy,
            $color,
            $tooltip,
        );
        $lines[] = sprintf(
            '<text x="%d" y="%d" font-family="sans-serif" font-size="10"'
            . ' fill="#333" dominant-baseline="middle">%s</text>',
            $cx + 9,
            $cy,
            $name,
        );
        $lines[] = '</g>';

        return implode("\n", $lines);
    }

    private function zoneColor(ZoneType $zone): string
    {
        return match ($zone) {
            ZoneType::PERFECT => '#4caf50',
            ZoneType::USEFULNESS => '#2196f3',
            ZoneType::PAIN => '#f44336',
            ZoneType::USELESSNESS => '#ff9800',
        };
    }

    private function renderLegend(): string
    {
        $y = self::PLOT_BOTTOM + 55;
        $spacing = 130;
        $entries = [
            ['color' => '#4caf50', 'label' => 'Perfect'],
            ['color' => '#2196f3', 'label' => 'Useful'],
            ['color' => '#f44336', 'label' => 'Zone of Pain'],
            ['color' => '#ff9800', 'label' => 'Zone of Uselessness'],
        ];

        $lines = [];

        foreach ($entries as $index => $entry) {
            $x = self::PLOT_LEFT + $index * $spacing;
            $lines[] = sprintf('<circle cx="%d" cy="%d" r="5" fill="%s"/>', $x, $y, $entry['color']);
            $lines[] = sprintf(
                '<text x="%d" y="%d" font-family="sans-serif" font-size="11"'
                . ' fill="#555" dominant-baseline="middle">%s</text>',
                $x + 10,
                $y,
                $entry['label'],
            );
        }

        return implode("\n", $lines);
    }
}
