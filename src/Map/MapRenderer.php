<?php

declare(strict_types=1);

namespace Stability\Map;

use Stability\Component\DependencyMap;

interface MapRenderer
{
    /**
     * Render the dependency graph to a string.
     *
     * @param list<list<string>> $cycles Each inner list is a set of component names forming a strongly
     *                                   connected component (i.e. a circular dependency group).
     */
    public function render(DependencyMap $map, array $cycles): string;

    /**
     * File extension for the rendered output (without the leading dot).
     */
    public function fileExtension(): string;
}
