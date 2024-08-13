<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Config\Module\Module;

readonly class Config
{
    /**
     * @param array<Module> $modules
     */
    public function __construct(
        public string $basePath,
        public array $modules,
    ) {
    }
}
