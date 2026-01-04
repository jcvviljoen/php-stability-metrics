<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Output\OutputSetting;

interface Config
{
    /**
     * The base path where the analysis is run from.
     */
    public function basePath(): string;

    /**
     * The list of modules to analyze within the project.
     *
     * @return list<Module>
     */
    public function modules(): array;

    /**
     * The output settings which determines where analysis results are written to.
     */
    public function outputSettings(): OutputSetting;

    public function overrideOutputSettings(OutputSetting $outputSettings): void;
}
