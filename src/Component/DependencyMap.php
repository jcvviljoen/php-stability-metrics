<?php

declare(strict_types=1);

namespace Stability\Component;

readonly class DependencyMap
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
     * As a simple example, take a component `Module3` holding a single class, which is
     * imported by one class in component `Module1`. `Module3` has a fan-in of 1.
     */
    public function countFanIn(Component $component): int
    {
        return array_reduce(
            $this->mappings,
            fn(int $fanIn, array $mapping) => $fanIn + $mapping[$component->module->name()],
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
     * Following the same example, `Module3` has a fan-out of 0, because its class imports
     * nothing, while `Module1` has a fan-out of 1.
     */
    public function countFanOut(Component $component): int
    {
        return array_reduce(
            $this->mappings[$component->module->name()],
            fn(int $fanOut, int $dependencyCount) => $fanOut + $dependencyCount,
            0,
        );
    }

    public static function from(ComponentCollection $components): self
    {
        $mappings = [];

        foreach ($components as $component) {
            $name = $component->module->name();

            foreach ($components as $other) {
                $otherName = $other->module->name();

                $mappings[$name][$otherName] = $component->countUsagesOf($other);
            }
        }

        return new self($mappings);
    }
}
