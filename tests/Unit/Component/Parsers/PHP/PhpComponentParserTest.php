<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP;

use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Component\File\Exception\InvalidFileException;
use Stability\Component\File\Metadata;
use Stability\Component\Parsers\PHP\PhpClassFileParser;
use Stability\Component\Parsers\PHP\PhpComponentParser;
use Stability\Component\Parsers\PHP\PhpFileReader;
use Stability\Component\Parsers\PHP\PhpNamespaceParser;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Component\MetadataFactory;
use Stability\Tests\_Fixtures\Config\ConfigFactory;
use Stability\Tests\ExpectThrows;

class PhpComponentParserTest extends TestCase
{
    use ExpectThrows;

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
        $config = ConfigFactory::module1();
        $module = $config->modules[0];
        $modulePath = $config->basePath . DIRECTORY_SEPARATOR . $module->name;
        $files = ['Abstract1.php', 'Class1.php', 'Interface1.php'];
        $this->setupParseFiles($files);
        $this->setupGetFilesForModule($modulePath, $files);
        $this->setupParsePrimaryNamespace([], 'tests\_Fixtures\_TestSrc\Module1');
        $this->setupParseFiles(
            $files,
            [
                MetadataFactory::abstract1(),
                MetadataFactory::class1(),
                MetadataFactory::interface1(),
            ],
        );

        $component = $this->parser->parse([$module]);

        $this->assertEquals(ComponentFactory::module1(), $component);
    }

    public function test_given_a_module_when_class_type_is_unknown_then_throw_exception(): void
    {
        $config = ConfigFactory::unknown();
        $module = $config->modules[0];
        $modulePath = $config->basePath . DIRECTORY_SEPARATOR . $module->name;
        $files = ['Unknown.txt'];
        $this->setupGetFilesForModule($modulePath, $files);
        $this->setupDetermineSharedNamespace($modulePath, 'tests\_Fixtures\_TestSrc\Module1');
        $this->setupParseFiles($files, [MetadataFactory::unknown()]);

        $exception = $this->expectThrows(fn() => $this->parser->parse($config, $module));

        $this->assertEquals(
            InvalidFileException::onInvalidFileType($files[0]),
            $exception,
        );
    }

    /**
     * @param array<string> $files
     * @param array<Metadata> $classData
     */
    private function setupParseFiles(array $files, array $classData): void
    {
        $this->fileParser
            ->expects($this->exactly(count($files)))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(...$classData);
    }

    /**
     * @param array<string> $files
     */
    private function setupGetFilesForModule(string $modulePath, array $files): void
    {
        $this->fileReader
            ->expects($this->once())
            ->method('files')
            ->with($modulePath, [])
            ->willReturn($files);
    }

    /**
     * @param array<string> $namespaces
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
