<?php

declare(strict_types=1);

namespace Stability\Infrastructure\PHP;

use Override;
use Stability\Component\Class\ClassType;
use Stability\Component\Component;
use Stability\Component\ComponentParser;
use Stability\Config\Config;
use Stability\Config\Module\Module;
use Stability\Exception\InvalidFileException;
use Stability\FileParser;
use Stability\FileReader;

readonly class PhpComponentParser implements ComponentParser
{
    public function __construct(
        private FileReader $fileReader,
        private FileParser $fileParser,
    ) {
    }

    #[Override] public function parse(Config $config, Module $module): Component
    {
        $abstractClasses = 0;
        $interfaces = 0;
        $totalClasses = 0;

        $modulePath = $config->basePath . DIRECTORY_SEPARATOR . $module->name;
        $moduleFiles = $this->fileReader->files($modulePath, $module->exclude);
        $sharedNamespace = $this->fileParser->determineSharedNamespace($modulePath);

        $classData = [];

        foreach ($moduleFiles as $file) {
            $data = $this->fileParser->parse($file, $sharedNamespace);

            switch ($data->type) {
                case ClassType::ABSTRACT_CLASS:
                    $abstractClasses++;
                    $totalClasses++;

                    break;
                case ClassType::CONCRETE_CLASS:
                case ClassType::ENUM:
                    $totalClasses++;

                    break;
                case ClassType::INTERFACE:
                    $interfaces++;
                    $totalClasses++;

                    break;
                case ClassType::UNKNOWN:
                    throw InvalidFileException::onInvalidFileType($file);
                /** @noinspection PhpUnusedSwitchBranchInspection */
                default:
                    throw InvalidFileException::onUnsupportedFileType($file, $data->type);
            }

            $classData[] = $data;
        }

        return new Component(
            $module,
            $sharedNamespace,
            $abstractClasses,
            $interfaces,
            $totalClasses,
            $classData,
        );
    }
}
