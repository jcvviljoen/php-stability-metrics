<?php

declare(strict_types=1);

namespace Stability\Tests\Feature;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Application\AnalyseProject;
use Stability\Application\AnalysisRequest;
use Stability\Component\ComponentCollection;
use Stability\Component\ComponentParser;
use Stability\Config\ConfigLoader;
use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;
use Stability\Graph\GraphOption;
use Stability\InstabilityAnalyser;
use Stability\Output\OutputWriterFactory;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Config\StabilityConfigFactory;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Covers where a run decides to put its files, which is the one place the request and the
 * configuration file both have an opinion.
 */
class AnalyseProjectTest extends TestCase
{
    use TemporaryDirectory;

    private const string CONFIG_FILE = 'irrelevant, the loader is stubbed';

    private string $configuredPath;
    private string $requestedPath;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->createTemporaryDirectory();

        mkdir($this->configuredPath = $this->temporaryDirectory . '/configured');
        mkdir($this->requestedPath = $this->temporaryDirectory . '/requested');
    }

    #[Override] protected function tearDown(): void
    {
        array_map('unlink', glob($this->configuredPath . '/*') ?: []);
        array_map('unlink', glob($this->requestedPath . '/*') ?: []);
        rmdir($this->configuredPath);
        rmdir($this->requestedPath);

        $this->removeTemporaryDirectory();

        parent::tearDown();
    }

    public function test_given_no_output_path_was_asked_for_then_the_configured_one_is_used(): void
    {
        $project = $this->projectConfiguredToWriteTo($this->configuredPath);

        $report = $project->handle(
            new AnalysisRequest(self::CONFIG_FILE, graph: GraphOption::MERMAID),
            new OutputWriterFactory(new NullOutput()),
        );

        $this->assertSame($this->configuredPath . '/stability-graph.mmd', $report->graphFile);
        $this->assertFileExists($this->configuredPath . '/stability-graph.mmd');
    }

    public function test_given_an_output_path_was_asked_for_then_it_wins_over_the_configured_one(): void
    {
        $project = $this->projectConfiguredToWriteTo($this->configuredPath);

        $report = $project->handle(
            new AnalysisRequest(self::CONFIG_FILE, outputPath: $this->requestedPath, graph: GraphOption::MERMAID),
            new OutputWriterFactory(new NullOutput()),
        );

        $this->assertSame($this->requestedPath . '/stability-graph.mmd', $report->graphFile);
        $this->assertFileExists($this->requestedPath . '/stability-graph.mmd');
        $this->assertFileDoesNotExist($this->configuredPath . '/stability-graph.mmd');
    }

    public function test_given_no_graph_or_chart_was_asked_for_then_neither_is_written(): void
    {
        $project = $this->projectConfiguredToWriteTo($this->configuredPath);

        $report = $project->handle(
            new AnalysisRequest(self::CONFIG_FILE),
            new OutputWriterFactory(new NullOutput()),
        );

        $this->assertNull($report->graphFile);
        $this->assertNull($report->chartFile);
        $this->assertEmpty(glob($this->configuredPath . '/*') ?: []);
    }

    private function projectConfiguredToWriteTo(string $path): AnalyseProject
    {
        $configLoader = $this->createStub(ConfigLoader::class);
        $configLoader
            ->method('load')
            ->willReturn(StabilityConfigFactory::baseValid(
                new OutputSetting(OutputOption::CONSOLE, $path, 'configured-name'),
            ));

        $componentParser = $this->createStub(ComponentParser::class);
        $componentParser
            ->method('parse')
            ->willReturn(new ComponentCollection([
                ComponentFactory::module1(),
                ComponentFactory::module2(),
                ComponentFactory::module3(),
            ]));

        return new AnalyseProject($configLoader, $componentParser, new InstabilityAnalyser());
    }
}
