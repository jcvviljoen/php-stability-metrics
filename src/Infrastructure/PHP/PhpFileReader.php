<?php

declare(strict_types=1);

namespace Stability\Infrastructure\PHP;

use Override;
use Stability\Exception\InvalidModuleException;
use Stability\FileReader;

/**
 * This class reads PHP files from the filesystem.
 */
readonly class PhpFileReader implements FileReader
{
    /**
     * @inheritDoc
     */
    #[Override] public function files(string $relativeDirectoryPath, array $exclude): array
    {
        $directory = realpath(rtrim($relativeDirectoryPath, DIRECTORY_SEPARATOR))
            ?: throw InvalidModuleException::onInvalidModulePath($relativeDirectoryPath);

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
