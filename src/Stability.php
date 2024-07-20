<?php

namespace Instability;

use Instability\Config\Config;
use Instability\Metric\Abstractness;
use Instability\Metric\DMS;
use Instability\Metric\Instability;

readonly class Stability
{
    private function __construct(
        private Config $config,
    ) {
    }

    public function check(): void
    {
        foreach ($this->config->modules as $module) {
            Instability::create();
        }

        Abstractness::create();
        DMS::create();
    }

    public static function create(Config $config): self
    {
        return new self($config);
    }
}
