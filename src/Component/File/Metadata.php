<?php

declare(strict_types=1);

namespace Stability\Component\File;

use Stability\Component\File\Exception\InvalidMetadataException;

readonly class Metadata
{
    /**
     * @param array<string> $imports
     */
    public function __construct(
        public Type $type,
        private string $namespace,
        public array $imports,
    ) {
    }

    public function namespace(): string
    {
        if (empty($this->namespace)) {
            throw InvalidMetadataException::onMissingNamespace();
        }

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
