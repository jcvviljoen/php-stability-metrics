<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Config;
use Stability\Config\ModuleList;
use Stability\Output\OutputSetting;

class LoadedConfig implements Config
{
    public function __construct(
        private readonly ModuleList $modules,
        private OutputSetting $outputSettings,
    ) {
    }

    #[Override] public function modules(): ModuleList
    {
        return $this->modules;
    }

    #[Override] public function outputSettings(): OutputSetting
    {
        return $this->outputSettings;
    }

    #[Override] public function overrideOutputSettings(OutputSetting $outputSettings): void
    {
        $this->outputSettings = $outputSettings;
    }
}
