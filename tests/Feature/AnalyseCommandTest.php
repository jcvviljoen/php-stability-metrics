<?php

declare(strict_types=1);

namespace Stability\Tests\Feature;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Application\AnalyseProject;
use Stability\Application\InitialiseConfig;
use Stability\Component\Parsers\ComponentParserFactory;
use Stability\Config\ConfigType;
use Stability\Config\Loaders\ConfigLoaderFactory;
use Stability\Console\AnalyseCommand;
use Stability\InstabilityAnalyser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Drives the command the way the binary does, against the test source tree.
 */
class AnalyseCommandTest extends TestCase
{
    use TemporaryDirectory;

    private const string CONFIG_TEST_SRC = 'tests/Feature/_Fixtures/config_test_src.php';
    private const string CONFIG_CYCLIC = 'tests/Feature/_Fixtures/config_cyclic.php';

    private CommandTester $command;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->createTemporaryDirectory();

        $this->command = new CommandTester($this->commandFor($this->projectRoot()));
    }

    #[Override] protected function tearDown(): void
    {
        $this->removeTemporaryDirectory();

        parent::tearDown();
    }

    public function test_given_a_configuration_then_report_the_metrics_of_every_component(): void
    {
        $this->command->execute(['--config' => self::CONFIG_TEST_SRC]);

        $this->command->assertCommandIsSuccessful();

        $display = $this->command->getDisplay();

        $this->assertStringContainsString('Calculating stability metrics...', $display);
        $this->assertStringContainsString('Component: Module1', $display);
        $this->assertStringContainsString('Component: Module2', $display);
        $this->assertStringContainsString('Component: Module3', $display);
        $this->assertStringContainsString('Stability metrics calculated successfully!', $display);
    }

    public function test_given_an_absolute_configuration_path_then_it_is_read_as_given(): void
    {
        $this->command->execute(['--config' => $this->projectRoot() . '/' . self::CONFIG_TEST_SRC]);

        $this->command->assertCommandIsSuccessful();
        $this->assertStringContainsString('Component: Module1', $this->command->getDisplay());
    }

    public function test_given_a_missing_configuration_then_fail_with_guidance(): void
    {
        $this->command->execute(['--config' => 'does-not-exist.php']);

        $this->assertSame(Command::FAILURE, $this->command->getStatusCode());
        $this->assertStringContainsString('Configuration file not found.', $this->command->getDisplay());
    }

    public function test_given_a_graph_and_a_chart_are_asked_for_then_both_are_written_to_the_output_path(): void
    {
        $this->command->execute([
            '--config' => self::CONFIG_TEST_SRC,
            '--output-path' => $this->temporaryDirectory,
            '--with-graph' => 'mermaid',
            '--with-chart' => 'svg',
        ]);

        $this->command->assertCommandIsSuccessful();

        $graph = $this->temporaryDirectory . '/stability-graph.mmd';
        $chart = $this->temporaryDirectory . '/stability-chart.svg';

        $this->assertFileExists($graph);
        $this->assertFileExists($chart);
        $this->assertStringContainsString("Dependency graph written to: $graph", $this->command->getDisplay());
        $this->assertStringContainsString("Stability chart written to: $chart", $this->command->getDisplay());
    }

    public function test_given_json_output_then_the_results_are_written_to_a_file(): void
    {
        $this->command->execute([
            '--config' => self::CONFIG_TEST_SRC,
            '--output' => 'json',
            '--output-path' => $this->temporaryDirectory,
            '--output-name' => 'metrics',
        ]);

        $this->command->assertCommandIsSuccessful();

        $written = $this->temporaryDirectory . '/metrics.json';

        $this->assertFileExists($written);
        $this->assertStringContainsString('"component": "Module1"', (string) file_get_contents($written));
    }

    public function test_given_an_unsupported_renderer_then_fail_before_doing_any_work(): void
    {
        $this->command->execute([
            '--config' => self::CONFIG_TEST_SRC,
            '--with-graph' => 'png',
        ]);

        $this->assertSame(Command::FAILURE, $this->command->getStatusCode());

        $display = $this->command->getDisplay();

        $this->assertStringContainsString('Unsupported graph renderer "png".', $display);
        $this->assertStringNotContainsString('Calculating stability metrics...', $display);
    }

    public function test_given_debug_then_the_stack_trace_is_shown(): void
    {
        $this->command->execute([
            '--config' => self::CONFIG_TEST_SRC,
            '--with-graph' => 'png',
            '--debug' => true,
        ]);

        $this->assertStringContainsString('Stack trace:', $this->command->getDisplay());
    }

    public function test_given_components_that_import_each_other_then_report_the_cycle(): void
    {
        $this->command->execute(['--config' => self::CONFIG_CYCLIC]);

        $this->command->assertCommandIsSuccessful();

        $display = $this->command->getDisplay();

        $this->assertStringContainsString('Circular dependencies detected:', $display);
        $this->assertStringContainsString('CycleA', $display);
        $this->assertStringContainsString('CycleB', $display);
    }

    public function test_given_init_when_no_configuration_exists_then_create_one(): void
    {
        $command = new CommandTester($this->commandFor($this->temporaryDirectory));

        $command->execute(['--init' => true]);

        $command->assertCommandIsSuccessful();
        $this->assertStringContainsString('created successfully', $command->getDisplay());
        $this->assertFileEquals(
            $this->projectRoot() . '/stability.php.sample',
            $this->temporaryDirectory . '/stability.php',
        );
    }

    public function test_given_init_when_a_configuration_already_exists_then_leave_it_alone(): void
    {
        $existing = $this->temporaryDirectory . '/stability.php';
        file_put_contents($existing, '<?php return [];');

        $command = new CommandTester($this->commandFor($this->temporaryDirectory));

        $command->execute(['--init' => true]);

        $command->assertCommandIsSuccessful();
        $this->assertStringContainsString('already exists', $command->getDisplay());
        $this->assertSame('<?php return [];', (string) file_get_contents($existing));
    }

    private function commandFor(string $basePath): AnalyseCommand
    {
        return new AnalyseCommand(
            $basePath,
            new AnalyseProject(
                ConfigLoaderFactory::create(ConfigType::PHP_ARRAY),
                ComponentParserFactory::create(),
                new InstabilityAnalyser(),
            ),
            new InitialiseConfig($this->projectRoot() . '/stability.php.sample'),
        );
    }

    private function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }
}
