<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Metadata;
use Stability\Component\File\MetadataCollection;

readonly class Component
{
    public function __construct(
        private string $name,
        private string $primaryNamespace,
        private MetadataCollection $fileData,
        public Thresholds $thresholds,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function primaryNamespace(): string
    {
        if (empty($this->primaryNamespace)) {
            throw InvalidComponentException::onEmptyComponent($this->name);
        }

        return $this->primaryNamespace;
    }

    public function countAbstractClasses(): int
    {
        return $this->fileData->countAbstractClasses();
    }

    public function countInterfaces(): int
    {
        return $this->fileData->countInterfaces();
    }

    public function countTotalClasses(): int
    {
        return $this->fileData->countTotalClasses();
    }

    public function countUsagesOf(Component $other): int
    {
        if ($this->name === $other->name) {
            return 0;
        }

        /** @var array<string> $imports */
        $imports = array_reduce(
            $this->fileData->validValues(),
            fn(array $carry, Metadata $class) => array_merge($carry, $class->imports),
            [],
        );

        $otherNamespace = $other->primaryNamespace();

        return count(array_filter(
            $imports,
            fn(string $import) => str_contains(strtolower($import), strtolower($otherNamespace)),
        ));
    }
}
