<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use Override;
use Stability\Component\Component;
use Stability\Component\ComponentCollection;
use Stability\Component\ComponentParser;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Exception\InvalidFileException;
use Stability\Component\File\Exception\InvalidMetadataException;
use Stability\Component\File\MetadataCollection;
use Stability\Component\File\Type;
use Stability\Config\ModuleList;

readonly class PhpComponentParser implements ComponentParser
{
    public function __construct(
        private PhpClassFileParser $fileParser,
        private PhpFileReader $fileReader,
        private PhpNamespaceParser $namespaceParser,
    ) {
    }

    /**
     * @throws InvalidMetadataException
     * @throws InvalidFileException
     * @throws InvalidComponentException
     */
    #[Override] public function parse(ModuleList $modules): ComponentCollection
    {
        $components = ComponentCollection::empty();

        foreach ($modules as $module) {
            $moduleFiles = $this->fileReader->files($module->path(), $module->exclude());

            $allFileMetadata = MetadataCollection::empty();

            foreach ($moduleFiles as $file) {
                $data = $this->fileParser->parse($file);

                if (Type::UNKNOWN === $data->type) {
                    throw InvalidFileException::onInvalidFileType($file);
                }

                $allFileMetadata->add($data);
            }

            $primaryNamespace = $this->namespaceParser->primaryNamespace($allFileMetadata->namespaces());

            $components->add(new Component($module, $primaryNamespace, $allFileMetadata));
        }

        return $components;
    }
}
