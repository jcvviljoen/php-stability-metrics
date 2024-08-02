<?php

declare(strict_types=1);

namespace Stability;

use Stability\Component\Class\ClassData;
use Stability\Config\Config;
use Stability\Config\Module\Module;

interface FileParser
{
    /**
     * This isn't the full namespace of a given file, but rather the namespace that all files in the module share.
     * It should take into account the base path of the project and the module's directory.
     *
     * @return string The shared namespace of the module.
     */
    public function determineSharedNamespace(string $modulePath): string;

    /**
     * @param string $filePath The path to the PHP file.
     * @param string $namespaceMatcher The namespace to match against.
     * @return ClassData The parsed file data.
     * If the file's class type cannot be determined, the result should contain FileType::UNKNOWN.
     * This allows the user to filter out files that are not relevant to the analysis.
     */
    public function parse(string $filePath, string $namespaceMatcher): ClassData;
}
