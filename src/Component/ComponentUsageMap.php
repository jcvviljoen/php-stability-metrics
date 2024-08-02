<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Tests\_Fixtures\_TestSrc\Module3\Class3;

readonly class ComponentUsageMap
{
    /**
     * @param array<string, array<string, int>> $mappings
     */
    public function __construct(public array $mappings)
    {
    }

    /**
     * Also known as "afferent coupling" (a reference to the nervous system).
     *
     * "Fan-in" is the number of incoming dependencies a component has
     * (dependency arrows point from other components to this component).
     *
     * It is the number of *classes* in **other** components
     * that depend on classes in this component.
     *
     * @see Class3 As a simple example, `Class3` (component `Module3`) in this project's
     * unit test fixtures has a fan-in of 1, because `Class1` (component `Module1`) imports it.
     */
    public function fanInDependencies(Component $component): int
    {
        return array_reduce(
            $this->mappings,
            fn(int $fanIn, array $mapping) => $fanIn + $mapping[$component->module->name],
            0,
        );
    }

    /**
     * Also known as "efferent coupling" (a reference to the nervous system).
     *
     * "Fan-out" is the number of outgoing dependencies a component has
     * (dependency arrows point from this component to other components).
     *
     * It is the number of *classes* in **this** component
     * that depend on classes in various other components.
     *
     * @see Class3 Again, as an example, `Class3` (component `Module3`) in this project's
     * unit test fixtures has a fan-out of 0, because it has no imports.
     * However, `Class1` (component `Module1`) has a fan-out of 1, because it imports `Class3`.
     */
    public function fanOutDependencies(Component $component): int
    {
        return array_reduce(
            $this->mappings[$component->module->name],
            fn(int $fanOut, int $dependencyCount) => $fanOut + $dependencyCount,
            0,
        );
    }

    /**
     * @param array<Component> $components
     */
    public static function from(array $components): self
    {
        $mappings = [];

        foreach ($components as $component) {
            $name = $component->module->name;

            foreach ($components as $other) {
                $otherName = $other->module->name;

                $mappings[$name][$otherName] = $component->countUsagesOf($other);
            }
        }

        return new self($mappings);
    }
}
