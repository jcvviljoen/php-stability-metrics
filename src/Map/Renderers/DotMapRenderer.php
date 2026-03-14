<?php

declare(strict_types=1);

namespace Stability\Map\Renderers;

use Override;
use Stability\Component\DependencyMap;
use Stability\Map\MapRenderer;

/**
 * Renders a dependency graph in Graphviz DOT format (.dot).
 *
 * Graphviz is free and open source (EPL-1.0). Install it via:
 *   brew install graphviz (macOS)
 *   apt install graphviz (Debian/Ubuntu)
 *
 * Convert the output to an image with:
 *   dot -Tsvg stability-map.dot -o stability-map.svg
 *   dot -Tpng stability-map.dot -o stability-map.png
 *
 * Components involved in circular dependencies are filled red.
 * Edges between components in the same cycle are drawn in red.
 */
readonly class DotMapRenderer implements MapRenderer
{
    #[Override] public function render(DependencyMap $map, array $cycles): string
    {
        $cycleNodeMap = $this->buildCycleNodeMap($cycles);
        $nodesWithEdges = [];
        $edgeLines = [];

        foreach ($map->mappings as $from => $targets) {
            foreach ($targets as $to => $count) {
                if ($count === 0 || $from === $to) {
                    continue;
                }

                $nodesWithEdges[$from] = true;
                $nodesWithEdges[$to] = true;

                $isCycleEdge = isset($cycleNodeMap[$from], $cycleNodeMap[$to])
                    && $cycleNodeMap[$from] === $cycleNodeMap[$to];

                $attrs = $isCycleEdge ? ' [color=red, penwidth=2.0]' : '';
                $edgeLines[] = "    \"$from\" -> \"$to\"$attrs;";
            }
        }

        $lines = [
            'digraph dependencies {',
            '    rankdir=LR;',
            '    node [shape=box, fontname="Helvetica"];',
            '',
        ];

        $lines = array_merge($lines, $edgeLines);

        // Standalone nodes (no edges)
        foreach (array_keys($map->mappings) as $node) {
            if (isset($nodesWithEdges[$node])) {
                continue;
            }

            $lines[] = "    \"$node\";";
        }

        // Highlight nodes involved in circular dependencies
        if (!empty($cycleNodeMap)) {
            $lines[] = '';

            foreach (array_keys($cycleNodeMap) as $node) {
                $lines[] = "    \"$node\" [style=filled, fillcolor=\"#ff6b6b\", fontcolor=white];";
            }
        }

        $lines[] = '}';

        return implode("\n", $lines) . "\n";
    }

    #[Override] public function fileExtension(): string
    {
        return 'dot';
    }

    /**
     * Returns a map of node name → SCC index, so we can tell whether two nodes
     * share the same strongly connected component (and therefore their edge is
     * part of a cycle).
     *
     * @param list<list<string>> $cycles
     * @return array<string, int>
     */
    private function buildCycleNodeMap(array $cycles): array
    {
        $map = [];

        foreach ($cycles as $index => $scc) {
            foreach ($scc as $node) {
                $map[$node] = $index;
            }
        }

        return $map;
    }
}
