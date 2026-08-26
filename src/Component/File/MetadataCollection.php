<?php

declare(strict_types=1);

namespace Stability\Component\File;

use Countable;
use IteratorAggregate;
use Override;
use Stability\Component\File\Exception\InvalidMetadataException;
use Traversable;

/**
 * @implements IteratorAggregate<int, Metadata>
 */
class MetadataCollection implements IteratorAggregate, Countable
{
    /**
     * @param array<int, Metadata> $items
     */
    public function __construct(private array $items)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(Metadata $metadata): void
    {
        $this->items[] = $metadata;
    }

    /**
     * The namespaces of every file that could be classified. Unclassified files have no
     * namespace to report, so they are left out rather than asked for one.
     *
     * @return list<string>
     *
     * @throws InvalidMetadataException
     */
    public function namespaces(): array
    {
        return array_values(
            array_unique(array_map(
                fn(Metadata $item) => $item->namespace(),
                $this->validValues(),
            )),
        );
    }

    public function countAbstractClasses(): int
    {
        $filtered = array_filter(
            $this->items,
            fn (Metadata $item) => $item->type->isAbstractClass(),
        );

        return count($filtered);
    }

    public function countInterfaces(): int
    {
        $filtered = array_filter(
            $this->items,
            fn (Metadata $item) => $item->type->isInterface(),
        );

        return count($filtered);
    }

    /**
     * How many files could not be classified, and so count towards nothing.
     */
    public function countUnclassified(): int
    {
        $filtered = array_filter(
            $this->items,
            fn (Metadata $item) => $item->type->isUnknown(),
        );

        return count($filtered);
    }

    public function countTotalClasses(): int
    {
        $filtered = array_filter(
            $this->items,
            fn (Metadata $item) => !$item->type->isUnknown(),
        );

        return count($filtered);
    }

    /**
     * @return array<int, Metadata>
     */
    public function validValues(): array
    {
        return array_values(array_filter(
            $this->items,
            fn(Metadata $item) => !$item->type->isUnknown(),
        ));
    }

    #[Override] public function count(): int
    {
        return count($this->items);
    }

    #[Override] public function getIterator(): Traversable
    {
        yield from $this->items;
    }
}
