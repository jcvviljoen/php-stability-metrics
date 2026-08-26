<?php

declare(strict_types=1);

namespace Stability\Application;

use Stability\Chart\ChartOption;
use Stability\Config\OutputOption;
use Stability\Graph\GraphOption;

/**
 * What the caller asked for.
 *
 * Everything except the configuration file is optional. Where a value is given it wins
 * over the same setting in the configuration file, and where it is not the file decides.
 */
readonly class AnalysisRequest
{
    /**
     * @param string $configFile The absolute path to the configuration file to analyse against.
     * @param OutputOption|null $outputOption The format to report the results in.
     * @param string|null $outputPath The directory to write files to, relative to the project's base path.
     * @param string|null $outputName The name of the results file, without an extension.
     * @param GraphOption|null $graph The renderer to draw a dependency graph with, if one is wanted.
     * @param ChartOption|null $chart The renderer to draw a stability chart with, if one is wanted.
     */
    public function __construct(
        public string $configFile,
        public ?OutputOption $outputOption = null,
        public ?string $outputPath = null,
        public ?string $outputName = null,
        public ?GraphOption $graph = null,
        public ?ChartOption $chart = null,
    ) {
    }
}
