<?php

declare(strict_types=1);

namespace Stability\Graph\Renderers;

use Override;
use Stability\Component\DependencyMap;
use Stability\Graph\GraphRenderer;

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
readonly class DotGraphRenderer implements GraphRenderer
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
                $edgeLines[] = sprintf(
                    '    "%s" -> "%s"%s;',
                    $this->escape((string) $from),
                    $this->escape((string) $to),
                    $attrs,
                );
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

            $lines[] = sprintf('    "%s";', $this->escape((string) $node));
        }

        // Highlight nodes involved in circular dependencies
        if (!empty($cycleNodeMap)) {
            $lines[] = '';

            foreach (array_keys($cycleNodeMap) as $node) {
                $lines[] = sprintf(
                    '    "%s" [style=filled, fillcolor="#ff6b6b", fontcolor=white];',
                    $this->escape((string) $node),
                );
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
     * Component names come from the user's configuration, so escape the
     * characters that would otherwise terminate a quoted DOT identifier.
     */
    private function escape(string $name): string
    {
        return str_replace(['\\', '"'], ['\\\\', '\\"'], $name);
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
