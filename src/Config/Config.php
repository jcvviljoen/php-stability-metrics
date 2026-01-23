<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Output\OutputSetting;

interface Config
{
    /**
     * The list of modules to analyze within the project.
     */
    public function modules(): ModuleList;

    /**
     * The output settings which determines where analysis results are written to.
     */
    public function outputSettings(): OutputSetting;

    public function overrideOutputSettings(OutputSetting $outputSettings): void;
}
