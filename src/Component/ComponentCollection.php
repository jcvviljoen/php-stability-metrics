<?php

declare(strict_types=1);

namespace Stability\Component;

use IteratorAggregate;
use Override;
use Traversable;

/**
 * @implements IteratorAggregate<int, Component>
 */
class ComponentCollection implements IteratorAggregate
{
    /**
     * @param array<int, Component> $components
     */
    public function __construct(
        private array $components,
    ) {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(Component $component): void
    {
        $this->components[] = $component;
    }

    public function dependencyMap(): DependencyMap
    {
        return DependencyMap::from($this);
    }

    /**
     * @return array<int, Component>
     */
    public function values(): array
    {
        return $this->components;
    }

    #[Override] public function getIterator(): Traversable
    {
        yield from $this->components;
    }
}
