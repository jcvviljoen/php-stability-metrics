<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Class\ClassData;
use Stability\Config\Module\Module;

readonly class Component
{
    /**
     * @param array<ClassData> $classData
     */
    public function __construct(
        public Module $module,
        public string $sharedNamespace,
        public int $abstractClasses,
        public int $interfaces,
        public int $totalClasses,
        private array $classData,
    ) {
    }

    public function countUsagesOf(Component $other): int
    {
        if ($this->module->name === $other->module->name) {
            return 0;
        }

        /** @var array<string> $imports */
        $imports = array_reduce(
            $this->classData,
            fn(array $carry, ClassData $class) => array_merge($carry, $class->imports),
            [],
        );

        return count(array_filter(
            $imports,
            fn(string $import) => str_contains(strtolower($import), strtolower($other->sharedNamespace)),
        ));
    }
}
