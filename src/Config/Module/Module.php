<?php

declare(strict_types=1);

namespace Stability\Config\Module;

readonly class Module
{
    /**
     * @param array<string> $exclude
     */
    public function __construct(
        public string $name,
        public array $exclude,
    ) {
    }
}
