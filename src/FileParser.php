<?php

namespace Stability;

use Stability\Component\FileData;

interface FileParser
{
    /**
     * @param string $filePath The path to the PHP file.
     * @param string $namespaceMatcher The namespace to match against.
     * @return FileData|null The parsed file data.
     * Will return `null` if the file's class type cannot be determined, or the file is not a `.php` file.
     */
    public function parse(string $filePath, string $namespaceMatcher): ?FileData;
}
