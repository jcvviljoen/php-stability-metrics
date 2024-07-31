<?php

namespace Stability\Config;

readonly class Module
{
    public function __construct(
        public string $module,
        public string $modulePath,
        public array $exclude,
    ) {
    }
}
