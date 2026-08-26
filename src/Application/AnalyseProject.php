<?php

declare(strict_types=1);

namespace Stability\Application;

use Stability\Application\Exception\UnwritableFileException;
use Stability\Chart\ChartOption;
use Stability\Chart\ChartRendererFactory;
use Stability\Component\ComponentDefinition;
use Stability\Component\ComponentParser;
use Stability\Component\DependencyMap;
use Stability\Component\Thresholds;
use Stability\Config\ConfigLoader;
use Stability\Config\Module;
use Stability\Config\ModuleList;
use Stability\Config\OutputSetting;
use Stability\Graph\CycleDetector;
use Stability\Graph\GraphOption;
use Stability\Graph\GraphRendererFactory;
use Stability\InstabilityAnalyser;
use Stability\Metric\Result;
use Stability\Output\OutputWriterFactory;
use Stability\StabilityException;

/**
 * One full pass of the analysis: read the configuration, parse the components it names,
 * calculate their metrics, and report on them however the request asked for.
 *
 * This is the one place allowed to know about every component at once. Translating what
 * the user configured into what the parser needs happens here, which is what keeps the
 * Config and Component components from having to know about each other.
 */
readonly class AnalyseProject
{
    private const string GRAPH_FILE_NAME = 'stability-graph';
    private const string CHART_FILE_NAME = 'stability-chart';

    public function __construct(
        private ConfigLoader $configLoader,
        private ComponentParser $componentParser,
        private InstabilityAnalyser $analyser,
    ) {
    }

    /**
     * @param OutputWriterFactory $writers The writers to report the results through. This is
     *  given per run rather than injected, because where console results go is the caller's
     *  to decide and only the caller holds the stream to send them to.
     *
     * @throws StabilityException
     */
    public function handle(AnalysisRequest $request, OutputWriterFactory $writers): AnalysisReport
    {
        $config = $this->configLoader->load($request->configFile);
        $settings = $this->resolveOutputSettings($config->outputSettings(), $request);

        $components = $this->componentParser->parse($this->definitionsFrom($config->modules()));
        $result = $this->analyser->analyse($components);

        $writers->create($settings)->outputResult($result);

        $dependencyMap = $components->dependencyMap();
        $cycles = CycleDetector::detect($dependencyMap);

        $graphFile = null !== $request->graph
            ? $this->writeGraph($request->graph, $settings, $dependencyMap, $cycles)
            : null;

        $chartFile = null !== $request->chart
            ? $this->writeChart($request->chart, $settings, $result)
            : null;

        return new AnalysisReport($result, $cycles, $graphFile, $chartFile);
    }

    /**
     * The request carries whatever the caller was explicit about, and the configuration
     * file answers for the rest.
     */
    private function resolveOutputSettings(OutputSetting $configured, AnalysisRequest $request): OutputSetting
    {
        return new OutputSetting(
            $request->outputOption ?? $configured->option,
            $request->outputPath ?? $configured->filePath(),
            $request->outputName ?? $configured->fileName(),
        );
    }

    /**
     * @return list<ComponentDefinition>
     *
     * @throws StabilityException
     */
    private function definitionsFrom(ModuleList $modules): array
    {
        return array_map(
            fn(Module $module) => new ComponentDefinition(
                $module->name(),
                $module->path(),
                new Thresholds($module->thresholdZoneOfPain(), $module->thresholdZoneOfUselessness()),
                $module->exclude(),
            ),
            $modules->values(),
        );
    }

    /**
     * @param list<list<string>> $cycles
     *
     * @throws UnwritableFileException
     */
    private function writeGraph(
        GraphOption $option,
        OutputSetting $settings,
        DependencyMap $dependencyMap,
        array $cycles,
    ): string {
        $renderer = GraphRendererFactory::create($option);

        return $this->write(
            $settings->pathFor(self::GRAPH_FILE_NAME, $renderer->fileExtension()),
            $renderer->render($dependencyMap, $cycles),
        );
    }

    /**
     * @throws UnwritableFileException
     */
    private function writeChart(ChartOption $option, OutputSetting $settings, Result $result): string
    {
        $renderer = ChartRendererFactory::create($option);

        return $this->write(
            $settings->pathFor(self::CHART_FILE_NAME, $renderer->fileExtension()),
            $renderer->render($result),
        );
    }

    /**
     * @throws UnwritableFileException
     */
    private function write(string $path, string $contents): string
    {
        if (false === @file_put_contents($path, $contents)) {
            throw UnwritableFileException::onFailedWrite($path);
        }

        return $path;
    }
}
