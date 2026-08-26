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
    /**
     * Mermaid keywords that cannot be used as a bare node id.
     */
    private const array RESERVED_IDS = [
        'graph',
        'subgraph',
        'end',
        'class',
        'classdef',
        'style',
        'click',
        'linkstyle',
        'direction',
        'flowchart',
        'o',
        'x',
    ];

    #[Override] public function render(DependencyMap $map, array $cycles): string
    {
        $nodeIds = $this->buildNodeIds($map);

        $lines = ['graph LR'];

        // Declare every node up front, so components without dependencies are drawn too
        foreach ($nodeIds as $name => $id) {
            $lines[] = $id === $name
                ? "    $id"
                : sprintf('    %s["%s"]', $id, $this->escapeLabel($name));
        }

        foreach ($map->mappings as $from => $targets) {
            foreach ($targets as $to => $count) {
                if ($count === 0 || $from === $to) {
                    continue;
                }

                $lines[] = "    {$nodeIds[$from]} --> {$nodeIds[$to]}";
            }
        }

        // Highlight nodes involved in circular dependencies
        foreach (array_keys($this->buildCycleNodeSet($cycles)) as $node) {
            $lines[] = "    style {$nodeIds[$node]} fill:#ff6b6b,stroke:#cc0000,color:#fff";
        }

        return implode("\n", $lines) . "\n";
    }

    #[Override] public function fileExtension(): string
    {
        return 'mmd';
    }

    /**
     * Maps each component name to a unique, Mermaid-safe node id.
     *
     * Component names come from the user's configuration, so they can contain
     * spaces and punctuation, none of which are valid in a Mermaid node id.
     * The original name is kept as the node's label.
     *
     * @return array<string, string>
     */
    private function buildNodeIds(DependencyMap $map): array
    {
        $names = array_keys($map->mappings);

        foreach ($map->mappings as $targets) {
            $names = array_merge($names, array_keys($targets));
        }

        $nodeIds = [];
        $taken = [];

        foreach ($names as $name) {
            $name = (string) $name;

            if (isset($nodeIds[$name])) {
                continue;
            }

            $id = $this->toNodeId($name);
            $unique = $id;
            $suffix = 2;

            while (isset($taken[$unique])) {
                $unique = $id . '_' . $suffix;
                $suffix++;
            }

            $taken[$unique] = true;
            $nodeIds[$name] = $unique;
        }

        return $nodeIds;
    }

    private function toNodeId(string $name): string
    {
        $id = trim((string) preg_replace('/[^A-Za-z0-9_]+/', '_', $name), '_');

        if ('' === $id || ctype_digit($id[0]) || in_array(strtolower($id), self::RESERVED_IDS, true)) {
            return "node_$id";
        }

        return $id;
    }

    private function escapeLabel(string $name): string
    {
        return str_replace('"', '#quot;', $name);
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
