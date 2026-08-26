<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use Override;
use Stability\Component\Component;
use Stability\Component\ComponentCollection;
use Stability\Component\ComponentDefinition;
use Stability\Component\ComponentParser;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Exception\InvalidFileException;
use Stability\Component\File\Exception\InvalidMetadataException;
use Stability\Component\File\MetadataCollection;
use Stability\Component\File\Type;

readonly class PhpComponentParser implements ComponentParser
{
    public function __construct(
        private PhpClassFileParser $fileParser,
        private PhpFileReader $fileReader,
        private PhpNamespaceParser $namespaceParser,
    ) {
    }

    /**
     * @param list<ComponentDefinition> $definitions
     *
     * @throws InvalidComponentException
     * @throws InvalidFileException
     * @throws InvalidMetadataException
     */
    #[Override] public function parse(array $definitions): ComponentCollection
    {
        $components = ComponentCollection::empty();

        foreach ($definitions as $definition) {
            $componentFiles = $this->fileReader->files($definition->path, $definition->exclude);

            $allFileMetadata = MetadataCollection::empty();

            foreach ($componentFiles as $file) {
                $data = $this->fileParser->parse($file);

                if (Type::UNKNOWN === $data->type) {
                    throw InvalidFileException::onInvalidFileType($file);
                }

                $allFileMetadata->add($data);
            }

            $primaryNamespace = $this->namespaceParser->primaryNamespace($allFileMetadata->namespaces());

            $components->add(new Component(
                $definition->name,
                $primaryNamespace,
                $allFileMetadata,
                $definition->thresholds,
            ));
        }

        return $components;
    }
}
