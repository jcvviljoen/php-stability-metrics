<?php

namespace Stability;

use Stability\Component\FileData;

interface FileParser
{
    /**
     * @param string $filePath The path to the PHP file.
     * @param string $namespaceMatcher The namespace to match against.
     * @return FileData The parsed file data.
     * If the file's class type cannot be determined, the result should contain FileType::UNKNOWN.
     * This allows the user to filter out files that are not relevant to the analysis.
     */
    public function parse(string $filePath, string $namespaceMatcher): FileData;
}
