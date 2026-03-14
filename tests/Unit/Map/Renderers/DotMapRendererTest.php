<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Map\Renderers;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\DependencyMap;
use Stability\Map\Renderers\DotMapRenderer;

class DotMapRendererTest extends TestCase
{
    private DotMapRenderer $renderer;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new DotMapRenderer();
    }

    public function test_file_extension_is_dot(): void
    {
        $this->assertEquals('dot', $this->renderer->fileExtension());
    }

    public function test_given_acyclic_graph_then_renders_valid_dot_structure(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 0, 'B' => 0, 'C' => 1],
            'C' => ['A' => 0, 'B' => 0, 'C' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringContainsString('digraph dependencies {', $output);
        $this->assertStringContainsString('rankdir=LR;', $output);
        $this->assertStringContainsString('"A" -> "B"', $output);
        $this->assertStringContainsString('"B" -> "C"', $output);
        $this->assertStringContainsString('}', $output);
    }

    public function test_given_acyclic_graph_then_no_red_highlights(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1],
            'B' => ['A' => 0, 'B' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringNotContainsString('color=red', $output);
        $this->assertStringNotContainsString('fillcolor', $output);
    }

    public function test_given_cycle_then_cycle_nodes_are_filled_red(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1],
            'B' => ['A' => 1, 'B' => 0],
        ]);
        $cycles = [['A', 'B']];

        $output = $this->renderer->render($map, $cycles);

        $this->assertStringContainsString('"A" [style=filled, fillcolor="#ff6b6b"', $output);
        $this->assertStringContainsString('"B" [style=filled, fillcolor="#ff6b6b"', $output);
    }

    public function test_given_cycle_then_intra_cycle_edges_are_red(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1],
            'B' => ['A' => 1, 'B' => 0],
        ]);
        $cycles = [['A', 'B']];

        $output = $this->renderer->render($map, $cycles);

        $this->assertStringContainsString('"A" -> "B" [color=red', $output);
        $this->assertStringContainsString('"B" -> "A" [color=red', $output);
    }

    public function test_given_cycle_then_non_cycle_nodes_are_not_highlighted(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 1, 'B' => 0, 'C' => 0],
            'C' => ['A' => 1, 'B' => 0, 'C' => 0],
        ]);
        $cycles = [['A', 'B']];

        $output = $this->renderer->render($map, $cycles);

        $this->assertStringNotContainsString('"C" [style=filled', $output);
    }

    public function test_given_edge_between_different_cycle_groups_then_not_highlighted(): void
    {
        // Cycle 1: A <-> B, Cycle 2: C <-> D, edge A->C crosses groups
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 1, 'D' => 0],
            'B' => ['A' => 1, 'B' => 0, 'C' => 0, 'D' => 0],
            'C' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 1],
            'D' => ['A' => 0, 'B' => 0, 'C' => 1, 'D' => 0],
        ]);
        // SCC index 0: A,B  |  SCC index 1: C,D
        $cycles = [['A', 'B'], ['C', 'D']];

        $output = $this->renderer->render($map, $cycles);

        // Cross-group edge A->C should not be red
        $this->assertStringContainsString('"A" -> "C";', $output);
        $this->assertStringNotContainsString('"A" -> "C" [color=red', $output);
    }

    public function test_given_zero_count_edges_then_not_rendered(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 0],
            'B' => ['A' => 0, 'B' => 0],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringNotContainsString('->', $output);
    }

    public function test_given_self_referencing_entries_then_not_rendered_as_edges(): void
    {
        $map = new DependencyMap([
            'A' => ['A' => 5, 'B' => 1],
            'B' => ['A' => 0, 'B' => 3],
        ]);

        $output = $this->renderer->render($map, []);

        $this->assertStringNotContainsString('"A" -> "A"', $output);
        $this->assertStringNotContainsString('"B" -> "B"', $output);
        $this->assertStringContainsString('"A" -> "B"', $output);
    }
}
