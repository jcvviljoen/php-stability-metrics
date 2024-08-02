<?php

declare(strict_types=1);

namespace Stability\Component\Class;

readonly class ClassData
{
    /**
     * @param array<string> $imports
     */
    public function __construct(
        public ClassType $type,
        public array $imports,
    ) {
    }
}
