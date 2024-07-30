<?php

namespace Instability;

use Exception;
use Instability\Component\Component;
use Instability\Component\ClassCounter;
use Instability\Component\FileData;
use Instability\Component\FileType;
use Instability\Config\Config;
use Instability\Config\Module;
use Instability\Metric\Calculator;

readonly class Stability
{
    private function __construct(
        private Config $config,
    ) {
    }

    public function check(): void
    {
        /** @var array<Component> $components */
        $components = [];

        foreach ($this->config->modules as $module) {
            $components[] = $this->parseToComponent($module);
        }

        $graph = $this->generateGraph($components);

        foreach ($components as $component) {
            echo "----------------------------------------\n";
            echo "Component: $component->name\n";
            echo "----------------------------------------\n";

            $abstractness = Calculator::abstractness( // TODO maybe not here?
                $component->counter->getAbstractClassCount(),
                $component->counter->getInterfaceCount(),
                $component->counter->getClassCount(),
            );
            echo "| Abstractness: $abstractness\n";

            $fanIn = array_reduce(
                $graph,
                fn(int $carry, array $item) => $carry + $item[$component->name],
                0,
            );
            $fanOut = array_reduce(
                $graph[$component->name],
                fn(int $carry, int $item) => $carry + $item,
                0,
            ); // TODO is fan in and fan out correct?

            // TODO can we show direction of dependencies?
            // TODO can we detect cycles and notify?
            $instability = Calculator::instability($fanIn, $fanOut);
            echo "| Instability: $instability\n";

            // TODO how to show "zone of pain" vs "zone of uselessness"?
            $dms = Calculator::dms($instability, $abstractness);
            echo "| DMS: $dms\n";

            echo "----------------------------------------\n";
            echo "\n";
        }
    }

    private function parseToComponent(Module $module): Component
    {
        /** @var array<FileData> $fileData */
        $fileData = [];
        $counter = ClassCounter::empty();

        $partialNamespace = str_replace('/', '\\', $module->modulePath);

        foreach ($module->files() as $file) {
            $data = $this->getFileData($file, $partialNamespace);

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

    private function getFileData(string $file, string $partialNamespace): ?FileData
    {
        $type = FileType::UNKNOWN;
        $imports = [];

        // Open the file for reading
        $file = fopen($file, 'r') ?: throw new Exception("Could not open file '$file'");

        while (($line = fgets($file)) !== false) {
            if (str_starts_with($line, 'use ')) {
                $imports[] = str_replace('use ', '', rtrim($line, ";\n"));
                continue;
            }

            if (str_contains($line, 'abstract class')) {
                $type = FileType::ABSTRACT_CLASS;
                break;
            }

            if (str_starts_with($line, 'interface')) {
                $type = FileType::INTERFACE;
                break;
            }

            if (str_contains($line, 'class')) {
                $type = FileType::T_CLASS;
                break;
            }

            // We don't need to read the file past the class definition.
            if (str_starts_with($line, '{') || str_ends_with($line, '{')) {
                break;
            }
        }

        fclose($file);

        if ($type === FileType::UNKNOWN) {
            return null;
        }

        /**
         * Only allow standard classes to be checked & filter out internal imports.
         */
        $imports = array_filter(
            $imports,
            fn(string $import) => str_contains($import, "\\")
                && !str_contains(strtolower($import), strtolower($partialNamespace))
        );

        return new FileData($imports, $type);
    }

    /**
     * @param array<Component> $components
     *
     * @return array<string, array<string>>
     */
    private function generateGraph(array $components): array
    {
        $graph = [];

        foreach ($components as $component) {
            foreach ($components as $otherComponent) {
                if ($component->name === $otherComponent->name) {
                    continue;
                }

                $graph[$component->name][$otherComponent->name] = $component->countUsagesOf($otherComponent);
            }
        }

        return $graph;
    }

    public static function create(Config $config): self
    {
        return new self($config);
    }
}
