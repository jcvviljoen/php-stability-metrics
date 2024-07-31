<?php

namespace Stability\Component;

readonly class FileData
{
    public function __construct(
        public array $imports,
        public FileType $type,
    ) {
    }
}
