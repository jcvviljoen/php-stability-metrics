<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP;

use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Component\ComponentCollection;
use Stability\Component\ComponentDefinition;
use Stability\Component\File\Metadata;
use Stability\Component\Parsers\PHP\PhpClassFileParser;
use Stability\Component\Parsers\PHP\PhpComponentParser;
use Stability\Component\Parsers\PHP\PhpFileReader;
use Stability\Component\Parsers\PHP\PhpNamespaceParser;
use Stability\Tests\_Fixtures\Component\ComponentDefinitionFactory;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Component\MetadataFactory;

class PhpComponentParserTest extends TestCase
{
    private PhpClassFileParser&MockObject $fileParser;
    private PhpFileReader&MockObject $fileReader;
    private PhpNamespaceParser&MockObject $namespaceParser;

    private PhpComponentParser $parser;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new PhpComponentParser(
            $this->fileParser = $this->createMock(PhpClassFileParser::class),
            $this->fileReader = $this->createMock(PhpFileReader::class),
            $this->namespaceParser = $this->createMock(PhpNamespaceParser::class),
        );
    }

    public function test_given_a_module_when_valid_then_parse(): void
    {
        $definitions = [ComponentDefinitionFactory::module1()];
        $componentPaths = array_map(fn(ComponentDefinition $definition) => $definition->path, $definitions);
        $files = ['Abstract1.php', 'Class1.php', 'Interface1.php'];
        $this->setupParseFiles(
            $files,
            [
                MetadataFactory::abstract1(),
                MetadataFactory::class1(),
                MetadataFactory::interface1(),
            ],
        );
        $this->setupGetFilesForComponent($componentPaths, $files);
        $this->setupParsePrimaryNamespace(
            // The single unique namespace from the parsed files
            ['Stability\Tests\_Fixtures\_TestSrc\Module1'],
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
        );

        $components = $this->parser->parse($definitions);

        $this->assertEquals(new ComponentCollection([ComponentFactory::module1()]), $components);
    }

    public function test_given_a_file_that_cannot_be_classified_then_leave_it_out_and_carry_on(): void
    {
        $definitions = [ComponentDefinitionFactory::module1()];
        $componentPaths = array_map(fn(ComponentDefinition $definition) => $definition->path, $definitions);
        $files = ['Class1.php', 'NotAType.txt'];
        $this->setupGetFilesForComponent($componentPaths, $files);
        $this->setupParseFiles($files, [MetadataFactory::class1(), MetadataFactory::unknown()]);
        $this->setupParsePrimaryNamespace(
            // The unclassified file has no namespace to contribute.
            ['Stability\\Tests\\_Fixtures\\_TestSrc\\Module1'],
            'Stability\\Tests\\_Fixtures\\_TestSrc\\Module1',
        );

        $component = $this->parser->parse($definitions)->values()[0];

        $this->assertEquals(1, $component->countTotalClasses());
        $this->assertEquals(1, $component->countUnclassifiedFiles());
    }

    /**
     * @param list<string> $files
     * @param list<Metadata> $classData
     */
    private function setupParseFiles(array $files, array $classData): void
    {
        $this->fileParser
            ->expects($this->exactly(count($files)))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(...$classData);
    }

    /**
     * @param list<string> $componentPaths
     * @param list<string> $files
     */
    private function setupGetFilesForComponent(array $componentPaths, array $files): void
    {
        $this->fileReader
            ->expects($this->exactly(count($componentPaths)))
            ->method('files')
            ->willReturn($files);
    }

    /**
     * @param list<string> $namespaces
     */
    private function setupParsePrimaryNamespace(array $namespaces, string $return): void
    {
        $this->namespaceParser
            ->expects($this->once())
            ->method('primaryNamespace')
            ->with($namespaces)
            ->willReturn($return);
    }
}
