<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Output\OutputSetting;

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
     * Override the output settings as needed.
     */
    public function overrideOutputSettings(OutputSetting $outputSettings): void;
}
