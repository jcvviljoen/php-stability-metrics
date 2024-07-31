<?php

namespace Stability\Component;

readonly class FileData
{
    /**
     * @param array<string> $imports
     */
    public function __construct(
        public array $imports,
        public FileType $type,
    ) {
    }
}
