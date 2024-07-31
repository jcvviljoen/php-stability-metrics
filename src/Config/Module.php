<?php

namespace Stability\Config;

readonly class Module
{
    /**
     * @param array<string> $exclude
     */
    public function __construct(
        public string $module,
        public string $modulePath,
        public array $exclude,
    ) {
    }
}
