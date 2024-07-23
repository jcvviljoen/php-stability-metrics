<?php

namespace Instability\Config;

use Instability\Exception\InvalidModuleException;

class Module
{
    /**
     * @var array<string>
     */
    private array $files = [];

    public function __construct(
        public readonly string $module,
        public readonly array $exclude,
    ) {
    }

    /**
     * @return array<string>
     */
    public function files(): array
    {
        if (!empty($this->files)) {
            return $this->files;
        }

        $directory = realpath(rtrim($this->module, '/'))
            ?: throw InvalidModuleException::onInvalidModulePath($this->module);

        $this->files = $this->rglob("$directory/*.php");

        return $this->files;
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
