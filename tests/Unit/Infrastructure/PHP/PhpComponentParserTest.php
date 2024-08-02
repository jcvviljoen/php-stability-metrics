<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Component\Class\ClassData;
use Stability\Exception\InvalidFileException;
use Stability\FileParser;
use Stability\FileReader;
use Stability\Infrastructure\PHP\PhpComponentParser;
use Stability\Tests\_Fixtures\Component\ClassDataFactory;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Config\ConfigFactory;
use Stability\Tests\ExpectThrows;

class PhpComponentParserTest extends TestCase
{
    use ExpectThrows;

    private FileReader&MockObject $fileReader;
    private FileParser&MockObject $fileParser;

    private PhpComponentParser $phpComponentParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileReader = $this->createMock(FileReader::class);
        $this->fileParser = $this->createMock(FileParser::class);

        $this->phpComponentParser = new PhpComponentParser($this->fileReader, $this->fileParser);
    }

    public function test_given_a_module_when_valid_then_parse(): void
    {
        $config = ConfigFactory::module1();
        $module = $config->modules[0];
        $modulePath = $config->basePath . DIRECTORY_SEPARATOR . $module->name;
        $files = ['Abstract1.php', 'Class1.php', 'Interface1.php'];
        $this->setupGetFilesForModule($modulePath, $files);
        $this->setupDetermineSharedNamespace($modulePath, 'tests\_Fixtures\_TestSrc\Module1');
        $this->setupParseFiles(
            $files,
            [
                ClassDataFactory::abstract1(),
                ClassDataFactory::class1(),
                ClassDataFactory::interface1(),
            ],
        );

        $component = $this->phpComponentParser->parse($config, $module);

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
        $this->setupParseFiles($files, [ClassDataFactory::unknown()]);

        $exception = $this->expectThrows(fn() => $this->phpComponentParser->parse($config, $module));

        $this->assertEquals(
            InvalidFileException::onInvalidFileType($files[0]),
            $exception,
        );
    }

    private function setupGetFilesForModule(string $modulePath, array $files): void
    {
        $this->fileReader->expects($this->once())
            ->method('files')
            ->with($modulePath)
            ->willReturn($files);
    }

    private function setupDetermineSharedNamespace(string $modulePath, string $sharedNamespace): void
    {
        $this->fileParser->expects($this->once())
            ->method('determineSharedNamespace')
            ->with($modulePath)
            ->willReturn($sharedNamespace);
    }

    /**
     * @param array<string> $files
     * @param array<ClassData> $classData
     */
    private function setupParseFiles(array $files, array $classData): void
    {
        $this->fileParser
            ->expects($this->exactly(count($files)))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(...$classData);
    }
}
