<?php

declare(strict_types=1);

namespace Stability;

use Stability\Exception\InvalidModuleException;

interface FileReader
{
    /**
     * @param string $relativeDirectoryPath The path to the module's directory to read files from.
     * This is **relative** to the base path specified in the config file.
     * This **is not** an absolute path.
     *
     * @param array<string> $exclude An array of file paths to exclude from the list of files.
     * This can be a relative path from the root of the project, or even just a plain filename
     * (to potentially exclude multiple files of the same name).
     *
     * @return array<string>
     *
     * @throws InvalidModuleException
     */
    public function files(string $relativeDirectoryPath, array $exclude): array;
}
