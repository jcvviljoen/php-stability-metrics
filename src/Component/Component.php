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
        $count = 0;

        if ($this->module->name === $other->module->name) {
            return $count;
        }

        foreach ($this->classData as $class) {
            foreach ($class->imports as $import) {
                if (str_contains(strtolower($import), strtolower($other->sharedNamespace))) {
                    $count++;

                    break;
                }
            }
        }

        return $count;
    }
}
