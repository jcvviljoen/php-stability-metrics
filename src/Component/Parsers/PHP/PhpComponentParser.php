<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use Override;
use Stability\Component\Component;
use Stability\Component\ComponentCollection;
use Stability\Component\ComponentParser;
use Stability\Component\File\Exception\InvalidFileException;
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

    #[Override] public function parse(array $modules): ComponentCollection
    {
        $components = ComponentCollection::empty();

        foreach ($modules as $module) {
            $moduleFiles = $this->fileReader->files($module->name(), $module->exclude());

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
