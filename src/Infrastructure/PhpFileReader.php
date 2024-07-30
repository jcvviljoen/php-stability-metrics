<?php

namespace Instability\Infrastructure;

use Instability\Config\Module;
use Instability\Exception\InvalidModuleException;
use Instability\FileReader;
use Override;

/**
 * This class reads PHP files from the filesystem.
 */
readonly class PhpFileReader implements FileReader
{
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
