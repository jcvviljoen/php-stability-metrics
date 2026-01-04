<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use Stability\Component\Exception\InvalidComponentException;

/**
 * This class reads PHP files from the filesystem.
 */
readonly class PhpFileReader
{
    /**
     * @param string $relativeDirectoryPath The path to the module's directory to read files from.
     * This is **relative** to the base path specified in the config file.
     *
     * @param array<string> $exclude An array of file paths to exclude from the list of files.
     * This can be a relative path from the root of the project, or even just a plain filename
     * (to potentially exclude multiple files of the same name).
     *
     * @return array<string>
     *
     * @throws InvalidComponentException
     */
    public function files(string $relativeDirectoryPath, array $exclude): array
    {
        $directory = realpath(rtrim($relativeDirectoryPath, DIRECTORY_SEPARATOR))
            ?: throw InvalidComponentException::onInvalidModulePath($relativeDirectoryPath);

        $files = $this->rglob("$directory/*.php");

        foreach ($exclude as $excludedFile) {
            $files = array_values(array_filter(
                $files,
                fn(string $file) => !str_contains($file, $excludedFile),
            ));
        }

        return $files;
    }

    /**
     * @return array<string>
     */
    private function rglob(string $pattern): array
    {
        $files = glob($pattern) ?: [];

        $directories = glob(
            dirname($pattern) . DIRECTORY_SEPARATOR . '*',
            GLOB_ONLYDIR | GLOB_NOSORT,
        ) ?: [];

        foreach ($directories as $dir) {
            $files = array_merge($files, $this->rglob($dir . DIRECTORY_SEPARATOR . basename($pattern)));
        }

        return $files;
    }
}
