<?php

declare(strict_types=1);

namespace Stability\Config;

use Override;

/**
 * The configuration as the file described it, which is all this reports. What the effective
 * settings for a run are, once a caller has had its say, is the application layer's to work
 * out.
 */
readonly class StabilityConfig implements Config
{
    private OutputSetting $outputSettings;

    public function __construct(
        private StabilityModuleList $modules,
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
}
