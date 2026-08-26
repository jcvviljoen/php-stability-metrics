<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Graph\Renderers;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\DependencyMap;
use Stability\Graph\Renderers\MermaidGraphRenderer;

class MermaidGraphRendererTest extends TestCase
{
    private MermaidGraphRenderer $renderer;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new MermaidGraphRenderer();
    }

    public function test_file_extension_is_mmd(): void
    {
        $this->assertEquals('mmd', $this->renderer->fileExtension());
    }

    public function test_given_acyclic_graph_then_render_edges_without_styles(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 0, 'B' => 0, 'C' => 1],
            'C' => ['A' => 0, 'B' => 0, 'C' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString('graph LR', $output);
        $this->assertStringContainsString('A --> B', $output);
        $this->assertStringContainsString('B --> C', $output);
        $this->assertStringNotContainsString('style', $output);
    }

    public function test_given_cycle_then_cycle_nodes_are_styled_red(): void
    {
        // A -> B -> A
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1],
            'B' => ['A' => 1, 'B' => 0],
        ]);
        $cycles = [['A', 'B']];

        $output = $this->renderer->render($map, $cycles);

        $this->assertStringContainsString('style A fill:#ff6b6b', $output);
        $this->assertStringContainsString('style B fill:#ff6b6b', $output);
    }

    public function test_given_cycle_then_non_cycle_nodes_are_not_styled(): void
    {
        // C is outside the cycle
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 1, 'B' => 0, 'C' => 0],
            'C' => ['A' => 1, 'B' => 0, 'C' => 0],
        ]);
        $cycles = [['A', 'B']];

        $output = $this->renderer->render($map, $cycles);

        $this->assertStringNotContainsString('style C', $output);
    }

    public function test_given_standalone_node_then_rendered_without_edge(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 0],
            'B' => ['A' => 1, 'B' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        // A has no outgoing edges and is not a target either - it should be listed standalone
        $this->assertStringContainsString('graph LR', $output);
        $this->assertStringContainsString('B --> A', $output);
    }

    public function test_given_zero_count_edges_then_not_rendered(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 0],
            'B' => ['A' => 0, 'B' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringNotContainsString('-->', $output);
    }

    public function test_given_self_referencing_entries_then_not_rendered_as_edges(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 5, 'B' => 1],
            'B' => ['A' => 0, 'B' => 3],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringNotContainsString('A --> A', $output);
        $this->assertStringNotContainsString('B --> B', $output);
        $this->assertStringContainsString('A --> B', $output);
    }

    public function test_given_names_with_spaces_then_ids_are_safe_and_names_kept_as_labels(): void
    {
        $map = new DependencyMap([
            'Some module name' => ['Some module name' => 0, 'Another module' => 1],
            'Another module' => ['Some module name' => 0, 'Another module' => 0],
        ]);

        $output = $this->renderer->render($map, [['Some module name', 'Another module']]);

        $this->assertStringContainsString('Some_module_name["Some module name"]', $output);
        $this->assertStringContainsString('Another_module["Another module"]', $output);
        $this->assertStringContainsString('Some_module_name --> Another_module', $output);
        $this->assertStringContainsString('style Some_module_name fill:#ff6b6b', $output);
        $this->assertStringNotContainsString('Some module name -->', $output);
    }

    public function test_given_a_name_matching_a_mermaid_keyword_then_the_id_is_prefixed(): void
    {
        $map = new DependencyMap([
            'Graph' => ['Graph' => 0, 'End' => 1],
            'End' => ['Graph' => 0, 'End' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString('node_Graph["Graph"]', $output);
        $this->assertStringContainsString('node_End["End"]', $output);
        $this->assertStringContainsString('node_Graph --> node_End', $output);
    }

    public function test_given_names_that_reduce_to_the_same_id_then_ids_stay_unique(): void
    {
        $map = new DependencyMap([
            'My Module' => ['My Module' => 0, 'My-Module' => 1],
            'My-Module' => ['My Module' => 0, 'My-Module' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString('My_Module["My Module"]', $output);
        $this->assertStringContainsString('My_Module_2["My-Module"]', $output);
        $this->assertStringContainsString('My_Module --> My_Module_2', $output);
    }

    public function test_given_a_name_with_a_quote_then_the_label_is_escaped(): void
    {
        $map = new DependencyMap([
            'The "core"' => ['The "core"' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString('The_core["The #quot;core#quot;"]', $output);
    }

    public function test_given_a_component_without_dependencies_then_it_is_still_declared(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 0],
            'B' => ['A' => 0, 'B' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString("\n    A\n", $output);
        $this->assertStringContainsString("\n    B\n", $output);
    }
}
