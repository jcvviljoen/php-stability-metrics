<?php

declare(strict_types=1);

namespace Stability\Component\File;

use Stability\Component\File\Exception\InvalidMetadataException;

readonly class Metadata
{
    /**
     * A file that could be classified sits in a namespace. One that could not has nothing
     * to report, which is the only case where an empty namespace is allowed.
     *
     * @param array<string> $imports
     *
     * @throws InvalidMetadataException
     */
    public function __construct(
        public Type $type,
        private string $namespace,
        public array $imports,
    ) {
        if (!$type->isUnknown() && empty($namespace)) {
            throw InvalidMetadataException::onMissingNamespace();
        }
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public static function unknown(): self
    {
        return new self(
            Type::UNKNOWN,
            '',
            [],
        );
    }
}
