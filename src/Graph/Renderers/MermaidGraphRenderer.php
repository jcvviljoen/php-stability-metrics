<?php

declare(strict_types=1);

namespace Stability\Graph\Renderers;

use Override;
use Stability\Component\DependencyMap;
use Stability\Graph\GraphRenderer;

/**
 * Renders a dependency graph as a Mermaid diagram (.mmd).
 *
 * The output can be previewed in VS Code (with the Mermaid extension),
 * rendered natively on GitHub in fenced code blocks, or opened in the
 * Mermaid Live Editor at https://mermaid.live.
 *
 * Components that are part of a circular dependency are highlighted in red.
 */
readonly class MermaidGraphRenderer implements GraphRenderer
{
    #[Override] public function render(DependencyMap $map, array $cycles): string
    {
        $cycleNodeSet = $this->buildCycleNodeSet($cycles);
        $nodesWithEdges = [];
        $edgeLines = [];

        foreach ($map->mappings as $from => $targets) {
            foreach ($targets as $to => $count) {
                if ($count === 0 || $from === $to) {
                    continue;
                }

                $nodesWithEdges[$from] = true;
                $nodesWithEdges[$to] = true;
                $edgeLines[] = "    $from --> $to";
            }
        }

        $lines = ['graph LR'];
        $lines = array_merge($lines, $edgeLines);

        // Standalone nodes (no edges)
        foreach (array_keys($map->mappings) as $node) {
            if (isset($nodesWithEdges[$node])) {
                continue;
            }

            $lines[] = "    {$node}";
        }

        // Highlight nodes involved in circular dependencies
        foreach (array_keys($cycleNodeSet) as $node) {
            $lines[] = "    style $node fill:#ff6b6b,stroke:#cc0000,color:#fff";
        }

        return implode("\n", $lines) . "\n";
    }

    #[Override] public function fileExtension(): string
    {
        return 'mmd';
    }

    /**
     * @param list<list<string>> $cycles
     * @return array<string, true>
     */
    private function buildCycleNodeSet(array $cycles): array
    {
        $set = [];

        foreach ($cycles as $scc) {
            foreach ($scc as $node) {
                $set[$node] = true;
            }
        }

        return $set;
    }
}
