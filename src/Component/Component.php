<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Metadata;
use Stability\Component\File\MetadataCollection;

readonly class Component
{
    /**
     * A component with no primary namespace has no classified files in it, which means the
     * configured path is wrong or everything under it was excluded. There is nothing to
     * measure either way, so it is rejected here rather than on first use.
     *
     * @throws InvalidComponentException
     */
    public function __construct(
        private string $name,
        private string $primaryNamespace,
        private MetadataCollection $fileData,
        public Thresholds $thresholds,
    ) {
        if (empty($primaryNamespace)) {
            throw InvalidComponentException::onEmptyComponent($name);
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function primaryNamespace(): string
    {
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

    /**
     * How many of the component's files the parser could not make sense of. They are left
     * out of every count, so a caller may want to say so rather than quietly drop them.
     */
    public function countUnclassifiedFiles(): int
    {
        return $this->fileData->countUnclassified();
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
            fn(string $import) => $this->importsFrom($import, $otherNamespace),
        ));
    }

    /**
     * Whether an import names something inside the given namespace.
     *
     * The comparison stops at a namespace boundary on purpose. Matching the namespace
     * anywhere in the import would have a component named "App\Foo" count every import
     * from "App\FooBar" as a dependency on itself, which quietly inflates the coupling
     * every metric here is calculated from.
     */
    private function importsFrom(string $import, string $namespace): bool
    {
        $imported = $this->normalise($import);

        return 0 === strcasecmp($imported, $namespace)
            || str_starts_with(strtolower($imported), strtolower($namespace) . '\\');
    }

    /**
     * The name an import refers to, without the parts that are not part of it: the kind of
     * import it is, and whatever it was aliased to locally.
     */
    private function normalise(string $import): string
    {
        foreach (['function ', 'const '] as $kind) {
            if (str_starts_with($import, $kind)) {
                $import = substr($import, strlen($kind));

                break;
            }
        }

        $alias = stripos($import, ' as ');

        return trim(false === $alias ? $import : substr($import, 0, $alias));
    }
}
