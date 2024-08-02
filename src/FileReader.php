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
     * @return array<string>
     *
     * @throws InvalidModuleException
     */
    public function files(string $relativeDirectoryPath): array;
}
