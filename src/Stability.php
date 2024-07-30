<?php

namespace Instability;

use Exception;
use Instability\Component\Component;
use Instability\Component\ClassCounter;
use Instability\Component\ComponentResult;
use Instability\Component\FileData;
use Instability\Component\FileType;
use Instability\Config\Config;
use Instability\Config\Module;
use Instability\Infrastructure\ConsoleOutputFormatter;
use Instability\Infrastructure\PhpFileParser;
use Instability\Infrastructure\PhpFileReader;
use Instability\Metric\Calculator;

readonly class Stability
{
    private function __construct(
        private Config $config,
        private FileReader $filesystemReader,
        private FileParser $fileParser,
        private OutputFormatter $outputFormatter,
    ) {
    }

    public function calculate(): void
    {
        /** @var array<Component> $components */
        $components = array_map(
            fn(Module $module) => $this->parseToComponent($module),
            $this->config->modules,
        );

        $graph = $this->generateComponentGraph($components);

        /** @var array<ComponentResult> $componentResults */
        $componentResults = array_map(
            fn(Component $component) => $this->calculateComponentMetrics($component, $graph),
            $components,
        );

        $result = new StabilityResult($componentResults);

        $this->outputFormatter->outputResult($result);
    }

    private function parseToComponent(Module $module): Component
    {
        /** @var array<FileData> $fileData */
        $fileData = [];
        $counter = ClassCounter::empty();

        $partialNamespace = str_replace('/', '\\', $module->modulePath);

        $moduleFiles = $this->filesystemReader->files($module);

        foreach ($moduleFiles as $file) {
            $data = $this->fileParser->parse($file, $partialNamespace);

            if ($data === null) {
                continue;
            }

            $fileData[] = $data;

            switch ($data->type) {
                case FileType::ABSTRACT_CLASS:
                    $counter->addAbstractClass();
                    break;
                case FileType::INTERFACE:
                    $counter->addInterface();
                    break;
                case FileType::T_CLASS:
                    $counter->addClass();
                    break;
                default:
                    break;
            }
        }

        return new Component($module->module, $partialNamespace, $fileData, $counter);
    }

    /**
     * @param array<Component> $components
     *
     * @return array<string, array<string, int>>
     */
    private function generateComponentGraph(array $components): array
    {
        $graph = [];

        foreach ($components as $component) {
            foreach ($components as $otherComponent) {
                if ($component->name === $otherComponent->name) {
                    $graph[$component->name][$otherComponent->name] = 0;
                }

                $graph[$component->name][$otherComponent->name] = $component->countUsagesOf($otherComponent);
            }
        }

        return $graph;
    }

    /**
     * @param array<string, array<string, int>> $componentGraph
     */
    private function calculateComponentMetrics(
        Component $component,
        array $componentGraph,
    ): ComponentResult {
        $abstractness = Calculator::abstractness( // TODO maybe not here?
            $component->counter->getAbstractClassCount(),
            $component->counter->getInterfaceCount(),
            $component->counter->getClassCount(),
        );

        $fanIn = array_reduce(
            $componentGraph,
            fn(int $carry, array $item) => $carry + $item[$component->name],
            0,
        );
        $fanOut = array_reduce(
            $componentGraph[$component->name],
            fn(int $carry, int $item) => $carry + $item,
            0,
        );

        // TODO can we show direction of dependencies?
        // TODO can we detect cycles and notify?
        $instability = Calculator::instability($fanIn, $fanOut);

        // TODO how to show "zone of pain" vs "zone of uselessness"?
        $dms = Calculator::dms($instability, $abstractness);

        return new ComponentResult(
            $component,
            $abstractness,
            $instability,
            $dms,
        );
    }

    public static function create(Config $config): self
    {
        return new self(
            $config,
            new PhpFileReader(),
            new PhpFileParser(),
            new ConsoleOutputFormatter(),
        );
    }
}
