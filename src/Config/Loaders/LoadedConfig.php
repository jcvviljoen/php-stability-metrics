<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Config;
use Stability\Output\OutputSetting;

class LoadedConfig implements Config
{
    /**
     * @param list<LoadedModule> $modules
     */
    public function __construct(
        private readonly string $basePath,
        private readonly array $modules,
        private OutputSetting $outputSettings,
    ) {
    }

    #[Override] public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return list<LoadedModule>
     */
    #[Override] public function modules(): array
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
