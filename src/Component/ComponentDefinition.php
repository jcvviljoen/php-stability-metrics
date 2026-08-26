<?php

declare(strict_types=1);

namespace Stability\Component;

/**
 * Everything a parser needs in order to read one component off disk.
 *
 * This is stated deliberately without reference to the configuration file the values
 * usually come from. The Config component describes what the user wrote; translating
 * that into definitions is the application layer's job. Naming a Config type here
 * would point Component at Config and close a dependency cycle.
 */
readonly class ComponentDefinition
{
    /**
     * @param string $name The component's unique name.
     * @param string $path The directory to read, relative to the project root.
     * @param Thresholds $thresholds When to report this component's distance from the main sequence.
     * @param array<string> $exclude Paths, or plain file names, to leave out of the component.
     */
    public function __construct(
        public string $name,
        public string $path,
        public Thresholds $thresholds,
        public array $exclude = [],
    ) {
    }
}
