<?php

declare(strict_types=1);

namespace Stability\Console;

use Override;
use Stability\Application\AnalyseProject;
use Stability\Application\AnalysisReport;
use Stability\Application\AnalysisRequest;
use Stability\Application\ConfigInitialisation;
use Stability\Application\InitialiseConfig;
use Stability\Chart\ChartOption;
use Stability\Config\OutputOption;
use Stability\Graph\GraphOption;
use Stability\Output\OutputWriterFactory;
use Stability\Shared\StabilityException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'stability',
    description: 'Calculate the stability metrics of the components in a project.',
)]
class AnalyseCommand extends Command
{
    public const string DEFAULT_CONFIG_FILE = 'stability.php';

    public function __construct(
        private readonly string $basePath,
        private readonly AnalyseProject $analyse,
        private readonly InitialiseConfig $initialise,
    ) {
        parent::__construct();
    }

    #[Override] protected function configure(): void
    {
        $this
            ->addOption(
                'init',
                'i',
                InputOption::VALUE_NONE,
                'Create the standard stability configuration file.',
            )
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to a custom stability configuration file.',
            )
            ->addOption(
                'output',
                null,
                InputOption::VALUE_REQUIRED,
                'The output format of the stability results. Can be "console" (default) or "json".',
            )
            ->addOption(
                'output-path',
                null,
                InputOption::VALUE_REQUIRED,
                'The directory <options=bold,underscore>relative to the project\'s base path</> to write'
                . ' output files to.',
            )
            ->addOption(
                'output-name',
                null,
                InputOption::VALUE_REQUIRED,
                'The name of the file that results are written to. Defaults to "stability-result".',
            )
            ->addOption(
                'with-graph',
                null,
                InputOption::VALUE_REQUIRED,
                'Also render a dependency graph. Available renderers: "mermaid" (.mmd), "dot" (Graphviz .dot).',
            )
            ->addOption(
                'with-chart',
                null,
                InputOption::VALUE_REQUIRED,
                'Also render a stability chart against the main sequence. Available renderers: "svg" (.svg).',
            )
            ->addOption(
                'fail-on-cycles',
                null,
                InputOption::VALUE_NONE,
                'Exit with a failure when a circular dependency is found, for use in a build.',
            )
            ->addOption(
                'debug',
                null,
                InputOption::VALUE_NONE,
                'Show debug information (exposes exception stack traces).',
            );
    }

    #[Override] protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configFile = $this->resolveConfigFile($this->stringOption($input, 'config'));

        try {
            if (true === $input->getOption('init')) {
                return $this->initialiseConfig($configFile, $output);
            }

            if (!is_file($configFile)) {
                $output->writeln(
                    '<error>Configuration file not found. Please run `stability --init (-i)`'
                    . ' or point to a custom file using `--config`.</error>',
                );

                return self::FAILURE;
            }

            $request = $this->requestFrom($input, $configFile);

            $output->writeln('<info>Calculating stability metrics...</info>');
            $output->writeln('');

            $report = $this->analyse->handle($request, new OutputWriterFactory($output));
        } catch (StabilityException $exception) {
            $this->reportException($exception, $input, $output);

            return self::FAILURE;
        }

        $this->reportOn($report, $output);

        if ($report->hasCycles() && true === $input->getOption('fail-on-cycles')) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * A path that is already absolute is taken as given, and anything else is read
     * relative to the project being analysed.
     */
    private function resolveConfigFile(?string $configured): string
    {
        $path = $configured ?? self::DEFAULT_CONFIG_FILE;

        if ($this->isAbsolute($path)) {
            return $path;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . $path;
    }

    private function isAbsolute(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || 1 === preg_match('#^[A-Za-z]:[\\\\/]#', $path);
    }

    /**
     * @throws StabilityException
     */
    private function requestFrom(InputInterface $input, string $configFile): AnalysisRequest
    {
        $outputOption = $this->stringOption($input, 'output');
        $graph = $this->stringOption($input, 'with-graph');
        $chart = $this->stringOption($input, 'with-chart');

        return new AnalysisRequest(
            $configFile,
            null !== $outputOption ? OutputOption::fromArgument($outputOption) : null,
            $this->stringOption($input, 'output-path'),
            $this->stringOption($input, 'output-name'),
            null !== $graph ? GraphOption::fromArgument($graph) : null,
            null !== $chart ? ChartOption::fromArgument($chart) : null,
        );
    }

    /**
     * @throws StabilityException
     */
    private function initialiseConfig(string $configFile, OutputInterface $output): int
    {
        $message = match ($this->initialise->handle($configFile)) {
            ConfigInitialisation::CREATED => 'Stability configuration file created successfully!',
            ConfigInitialisation::ALREADY_EXISTED => 'Stability configuration file already exists!',
        };

        $output->writeln("<info>$message</info>");

        return self::SUCCESS;
    }

    private function reportOn(AnalysisReport $report, OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln('<info>Stability metrics calculated successfully!</info>');

        if (null !== $report->graphFile) {
            $output->writeln("<info>Dependency graph written to: {$report->graphFile}</info>");
        }

        if (null !== $report->chartFile) {
            $output->writeln("<info>Stability chart written to: {$report->chartFile}</info>");
        }

        foreach ($report->unclassifiedFiles as $component => $count) {
            $output->writeln(
                "<comment>$component: $count file(s) could not be classified and were left out"
                . ' of its counts.</comment>',
            );
        }

        if (!$report->hasCycles()) {
            return;
        }

        $output->writeln('');
        $output->writeln('<error>Circular dependencies detected:</error>');

        foreach ($report->cycles as $cycle) {
            $output->writeln($this->describeCycle($cycle));
        }
    }

    /**
     * Two components that import each other is a cycle you can read as a path, and reading
     * it that way is the point: those are the two imports to break.
     *
     * Anything larger is a strongly connected group. Every member reaches every other one,
     * but not in the order they happen to be listed, so drawing arrows between them would
     * claim a route around the group that need not exist. A project whose components have
     * knotted together produces one of these covering most of them, and saying so plainly
     * is more use than a fabricated forty-step lap.
     *
     * @param list<string> $cycle
     */
    private function describeCycle(array $cycle): string
    {
        if (2 === count($cycle)) {
            return '<comment>  ' . implode(' -> ', $cycle) . ' -> ' . $cycle[0] . '</comment>';
        }

        sort($cycle);

        return sprintf(
            '<comment>  %d components that all depend on each other, directly or by way of'
            . ' the others: %s</comment>',
            count($cycle),
            implode(', ', $cycle),
        );
    }

    private function reportException(
        StabilityException $exception,
        InputInterface $input,
        OutputInterface $output,
    ): void {
        $output->writeln("<error>{$exception->getMessage()}</error>");

        if (true !== $input->getOption('debug') && !$output->isVerbose()) {
            return;
        }

        $output->writeln([
            '',
            '<error>Stack trace:</error>',
            '<info>' . $exception->getTraceAsString() . '</info>',
        ]);
    }

    private function stringOption(InputInterface $input, string $name): ?string
    {
        $value = $input->getOption($name);

        return is_string($value) ? $value : null;
    }
}
