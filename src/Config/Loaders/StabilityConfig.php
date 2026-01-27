<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Config;
use Stability\Output\OutputSetting;

class StabilityConfig implements Config
{
    private OutputSetting $outputSettings;

    public function __construct(
        private readonly StabilityModuleList $modules,
        ?OutputSetting $outputSettings = null,
    ) {
        $this->outputSettings = $outputSettings ?? OutputSetting::default();
    }

    #[Override] public function modules(): StabilityModuleList
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
