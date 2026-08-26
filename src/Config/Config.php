<?php

declare(strict_types=1);

namespace Stability\Config;

interface Config
{
    /**
     * The list of modules to analyse within the project.
     */
    public function modules(): ModuleList;

    /**
     * The output settings which determines where analysis results are written to.
     */
    public function outputSettings(): OutputSetting;

    /**
     * A copy of this configuration with the given output settings in place of its own,
     * so that a caller (the CLI, say) can layer its own settings over the file's.
     */
    public function withOutputSettings(OutputSetting $outputSettings): self;
}
