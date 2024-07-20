<?php

namespace Instability\Config;

readonly class Module
{
    public function __construct(
        public string $module,
        public array $exclude,
    ) {
    }
}
