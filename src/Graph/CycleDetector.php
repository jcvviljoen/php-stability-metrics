<?php

declare(strict_types=1);

namespace Stability\Graph;

use Stability\Component\DependencyMap;

/**
 * Detects circular dependencies in a {@see DependencyMap} using Tarjan's
 * Strongly Connected Components (SCC) algorithm.
 *
 * Any SCC containing more than one node represents a set of components that
 * mutually depend on each other (i.e. a circular dependency).
 */
final class CycleDetector
{
    private int $timer = 0;

    /** @var array<string, int> */
    private array $discovered = [];

    /** @var array<string, int> */
    private array $low = [];

    /** @var array<string, bool> */
    private array $onStack = [];

    /** @var list<string> */
    private array $stack = [];

    /** @var list<list<string>> */
    private array $sccs = [];

    /**
     * Returns all circular dependency groups found in the map.
     *
     * Each returned list contains the names of the components that form a
     * strongly connected component with more than one node — i.e. they
     * all (directly or transitively) depend on each other.
     *
     * @return list<list<string>>
     */
    public static function detect(DependencyMap $map): array
    {
        $detector = new self();

        foreach (array_keys($map->mappings) as $node) {
            if (isset($detector->discovered[$node])) {
                continue;
            }

            $detector->dfs($node, $map->mappings);
        }

        return array_values(
            array_filter($detector->sccs, fn(array $scc) => count($scc) > 1),
        );
    }

    /** @param array<string, array<string, int>> $mappings */
    private function dfs(string $node, array $mappings): void
    {
        $this->discovered[$node] = $this->low[$node] = $this->timer;
        $this->timer++;
        $this->onStack[$node] = true;
        $this->stack[] = $node;

        foreach ($mappings[$node] ?? [] as $neighbour => $count) {
            if ($count === 0) {
                continue;
            }

            if (!isset($this->discovered[$neighbour])) {
                $this->dfs($neighbour, $mappings);
                $this->low[$node] = min($this->low[$node], $this->low[$neighbour]);
            } elseif ($this->onStack[$neighbour]) {
                $this->low[$node] = min($this->low[$node], $this->discovered[$neighbour]);
            }
        }

        if ($this->low[$node] !== $this->discovered[$node]) {
            return;
        }

        $scc = [];

        do {
            /** @var string $w — stack always contains $node, so pop is never null here */
            $w = array_pop($this->stack);
            $this->onStack[$w] = false;
            $scc[] = $w;
        } while ($w !== $node);

        $this->sccs[] = $scc;
    }
}
