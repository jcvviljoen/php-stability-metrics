<?php

namespace Stability\Infrastructure;

use Override;
use Stability\Config\Module;
use Stability\Exception\InvalidModuleException;
use Stability\FileReader;

/**
 * This class reads PHP files from the filesystem.
 */
readonly class PhpFileReader implements FileReader
{
    /**
     * @return array<string>
     */
    #[Override] public function files(Module $module): array
    {
        $directory = realpath(rtrim($module->modulePath, '/'))
            ?: throw InvalidModuleException::onInvalidModulePath($module->modulePath);

        return $this->rglob("$directory/*.php");
    }

    /**
     * @return array<string>
     */
    private function rglob(string $pattern): array
    {
        $files = glob($pattern) ?: [];

        $directories = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) ?: [];

        foreach ($directories as $dir) {
            $files = array_merge($files, $this->rglob($dir . '/' . basename($pattern)));
        }

        return $files;
    }
}
