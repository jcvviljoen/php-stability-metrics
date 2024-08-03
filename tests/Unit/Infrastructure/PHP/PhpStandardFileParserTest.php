<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP;

use Exception;
use PHPUnit\Framework\TestCase;
use Stability\Component\Class\ClassData;
use Stability\Component\Class\ClassType;
use Stability\Infrastructure\PHP\PhpStandardFileParser;
use Stability\Tests\ExpectThrows;

/**
 * This test uses the files in the "_Fixtures/Parser" directory.
 * The classes therein have been further separated into different directories
 * so that we can check the imports.
 *
 * We also test against the "_Fixtures\Other" namespace to ensure that the parser
 * actually includes imports in the class data.
 */
class PhpStandardFileParserTest extends TestCase
{
    use ExpectThrows;

    private PhpStandardFileParser $phpFileParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->phpFileParser = new PhpStandardFileParser();
    }

    public function test_determine_shared_namespace(): void
    {
        $modulePath = 'path/to/module';

        $sharedNamespace = $this->phpFileParser->determineSharedNamespace($modulePath);

        $this->assertEquals('path\to\module', $sharedNamespace);
    }

    public function test_given_a_path_when_file_is_abstract_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parser/Abstraction/TestAbstractClass.php';

        $classData = $this->phpFileParser->parse($file, '_Fixtures\Other');

        $this->assertEquals(new ClassData(ClassType::ABSTRACT_CLASS, []), $classData);
    }

    public function test_given_a_path_when_file_is_interface_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parser/Abstraction/TestInterface.php';

        $classData = $this->phpFileParser->parse($file, '_Fixtures\Other');

        $this->assertEquals(
            new ClassData(
                ClassType::INTERFACE,
                ['Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\TestEnum'],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_concrete_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parser/TestClass.php';

        $classData = $this->phpFileParser->parse($file, '_Fixtures\Other');

        $this->assertEquals(
            new ClassData(
                ClassType::CONCRETE_CLASS,
                [
                    'Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\Abstraction\TestAbstractClass',
                    'Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\Abstraction\TestInterface',
                ],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_enum_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parser/TestEnum.php';

        $classData = $this->phpFileParser->parse($file, '_Fixtures\Other');

        $this->assertEquals(new ClassData(ClassType::ENUM, []), $classData);
    }

    public function test_given_a_path_when_file_is_unknown_then_parse_to_empty_class_data(): void
    {
        $file = __DIR__ . '/_Fixtures/Parser/unknown.php';

        $classData = $this->phpFileParser->parse($file, '_Fixtures\Other');

        $this->assertEquals(new ClassData(ClassType::UNKNOWN, []), $classData);
    }

    public function test_given_a_path_when_file_cannot_be_read_then_throw_exception(): void
    {
        $file = 'path/to/file';

        $exception = $this->expectThrows(
            fn() => $this->phpFileParser->parse($file, ''),
        );

        $this->assertEquals(
            new Exception("Could not open file \"$file\" for reading."),
            $exception,
        );
    }
}
