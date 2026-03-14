<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Graph;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Graph\GraphOption;
use Stability\Graph\GraphRendererFactory;
use Stability\Graph\Renderers\DotGraphRenderer;
use Stability\Graph\Renderers\MermaidGraphRenderer;

class GraphRendererFactoryTest extends TestCase
{
    /**
     * @param class-string $expected
     */
    #[DataProvider('provide_graph_options')]
    public function test_create_graph_renderer(GraphOption $option, string $expected): void
    {
        $renderer = GraphRendererFactory::create($option);

        $this->assertInstanceOf($expected, $renderer);
    }

    /**
     * @return array<string, array{option: GraphOption, expected: class-string}>
     */
    public static function provide_graph_options(): array
    {
        return [
            'When given a "mermaid" option, then provide the Mermaid renderer' => [
                'option' => GraphOption::MERMAID,
                'expected' => MermaidGraphRenderer::class,
            ],
            'When given a "dot" option, then provide the Graphviz DOT renderer' => [
                'option' => GraphOption::DOT,
                'expected' => DotGraphRenderer::class,
            ],
        ];
    }
}
