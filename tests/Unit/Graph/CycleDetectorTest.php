<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Graph;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Component\DependencyMap;
use Stability\Graph\CycleDetector;

class CycleDetectorTest extends TestCase
{
    public function test_given_empty_map_then_no_cycles_detected(): void
    {
        $map = new DependencyMap([]);

        $cycles = CycleDetector::detect($map);

        $this->assertEmpty($cycles);
    }

    public function test_given_acyclic_graph_then_no_cycles_detected(): void
    {
        // A -> B -> C  (no cycle)
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 0, 'B' => 0, 'C' => 1],
            'C' => ['A' => 0, 'B' => 0, 'C' => 0],
        ]);

        $cycles = CycleDetector::detect($map);

        $this->assertEmpty($cycles);
    }

    public function test_given_direct_cycle_then_cycle_is_detected(): void
    {
        // A -> B -> A
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1],
            'B' => ['A' => 1, 'B' => 0],
        ]);

        $cycles = CycleDetector::detect($map);

        $this->assertCount(1, $cycles);
        $this->assertEqualsCanonicalizing(['A', 'B'], $cycles[0]);
    }

    public function test_given_three_node_cycle_then_all_members_detected(): void
    {
        // A -> B -> C -> A
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 0, 'B' => 0, 'C' => 1],
            'C' => ['A' => 1, 'B' => 0, 'C' => 0],
        ]);

        $cycles = CycleDetector::detect($map);

        $this->assertCount(1, $cycles);
        $this->assertEqualsCanonicalizing(['A', 'B', 'C'], $cycles[0]);
    }

    public function test_given_multiple_independent_cycles_then_all_detected(): void
    {
        // Cycle 1: A <-> B
        // Cycle 2: C <-> D
        // E has no cycle
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0, 'D' => 0, 'E' => 0],
            'B' => ['A' => 1, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0],
            'C' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 1, 'E' => 0],
            'D' => ['A' => 0, 'B' => 0, 'C' => 1, 'D' => 0, 'E' => 0],
            'E' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0],
        ]);

        $cycles = CycleDetector::detect($map);

        $this->assertCount(2, $cycles);

        $allNodes = array_merge(...$cycles);
        $this->assertEqualsCanonicalizing(['A', 'B', 'C', 'D'], $allNodes);
    }

    public function test_given_node_outside_cycle_then_not_included(): void
    {
        // A -> B -> C -> B  (A feeds into the cycle but is not part of it)
        $map = new DependencyMap([
            'A' => ['A' => 0, 'B' => 1, 'C' => 0],
            'B' => ['A' => 0, 'B' => 0, 'C' => 1],
            'C' => ['A' => 0, 'B' => 1, 'C' => 0],
        ]);

        $cycles = CycleDetector::detect($map);

        $this->assertCount(1, $cycles);
        $this->assertEqualsCanonicalizing(['B', 'C'], $cycles[0]);
        $this->assertNotContains('A', $cycles[0]);
    }

    #[DataProvider('provideGraphsWithNoCycles')]
    public function test_given_acyclic_graphs_then_no_cycles_detected(DependencyMap $map): void
    {
        $cycles = CycleDetector::detect($map);

        $this->assertEmpty($cycles);
    }

    /** @return array<string, array{map: DependencyMap}> */
    public static function provideGraphsWithNoCycles(): array
    {
        return [
            'Single node with no self-dependency' => [
                'map' => new DependencyMap(['A' => ['A' => 0]]),
            ],
            'Linear chain' => [
                'map' => new DependencyMap([
                    'A' => ['A' => 0, 'B' => 1, 'C' => 0],
                    'B' => ['A' => 0, 'B' => 0, 'C' => 1],
                    'C' => ['A' => 0, 'B' => 0, 'C' => 0],
                ]),
            ],
            'Zero-count edges are ignored' => [
                'map' => new DependencyMap([
                    'A' => ['A' => 0, 'B' => 0],
                    'B' => ['A' => 0, 'B' => 0],
                ]),
            ],
        ];
    }
}
