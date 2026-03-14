<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Map;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Map\MapOption;
use Stability\Map\MapRendererFactory;
use Stability\Map\Renderers\DotMapRenderer;
use Stability\Map\Renderers\MermaidMapRenderer;

class MapRendererFactoryTest extends TestCase
{
    /**
     * @param class-string $expected
     */
    #[DataProvider('provide_map_options')]
    public function test_create_map_renderer(MapOption $option, string $expected): void
    {
        $renderer = MapRendererFactory::create($option);

        $this->assertInstanceOf($expected, $renderer);
    }

    /**
     * @return array<string, array{option: MapOption, expected: class-string}>
     */
    public static function provide_map_options(): array
    {
        return [
            'When given a "mermaid" option, then provide the Mermaid renderer' => [
                'option' => MapOption::MERMAID,
                'expected' => MermaidMapRenderer::class,
            ],
            'When given a "dot" option, then provide the Graphviz DOT renderer' => [
                'option' => MapOption::DOT,
                'expected' => DotMapRenderer::class,
            ],
        ];
    }
}
