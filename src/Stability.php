<?php

namespace Instability;

use Exception;
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
        foreach ($this->config->modules as $module) {
            $counter = $this->countClasses($module);

            $abstractness = Calculator::abstractness(
                $counter->getAbstractClassCount(),
                $counter->getInterfaceCount(),
                $counter->getClassCount(),
            );

            var_dump("Abstractness for module $module->module: $abstractness");
            echo "Abstractness for module $module->module: $abstractness";

            //foreach ($module->files() as $file) {
            //    $instability = Instability::calculate($external, $internal);
            //    echo "Instability for file $file: $instability";
            //
            //    $dms = DMS::calculate($instability, $abstractness);
            //    echo "DMS for file $file: $dms";
            //}
        }
    }

    private function countClasses(Module $module): Counter
    {
        $counter = Counter::empty();

        foreach ($module->files() as $file) {
            $fileType = $this->getFileType($file);

            switch ($fileType) {
                case FileType::ABSTRACT_CLASS:
                    $counter->addClass();
                    $counter->addAbstractClass();
                    break;
                case FileType::INTERFACE:
                    $counter->addClass();
                    $counter->addInterface();
                    break;
                case FileType::T_CLASS:
                    $counter->addClass();
                    break;
                default:
                    break;
            }
        }

        return $counter;
    }

    private function getFileType(string $file): FileType
    {
        $type = FileType::UNKNOWN;

        // Open the file for reading
        $file = fopen($file, 'r') ?: throw new Exception("Could not open file '$file'");

        while (($line = fgets($file)) !== false) {
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

        return $type;
    }

    public static function create(Config $config): self
    {
        return new self($config);
    }
}
