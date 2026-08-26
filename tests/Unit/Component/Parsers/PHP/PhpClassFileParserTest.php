<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\File\Metadata;
use Stability\Component\File\Type;
use Stability\Component\Parsers\PHP\PhpClassFileParser;
use Stability\Tests\_Fixtures\Component\MetadataFactory;
use Stability\Tests\ExpectThrows;

/**
 * This test uses the files in the "_Fixtures" directory.
 */
class PhpClassFileParserTest extends TestCase
{
    use ExpectThrows;

    private PhpClassFileParser $parser;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new PhpClassFileParser();
    }

    public function test_given_a_path_when_file_is_abstract_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/Abstraction/TestAbstractClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::ABSTRACT_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_an_abstract_readonly_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/Abstraction/AbstractReadonlyClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::ABSTRACT_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_interface_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/Abstraction/TestInterface.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::INTERFACE,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction',
                ['Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\TestEnum'],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_concrete_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/TestClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing',
                [
                    'Override',
                    'RuntimeException',
                    'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction\TestAbstractClass',
                    'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction\TestInterface',
                ],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_closed_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/ClosedClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_readonly_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/ReadonlyClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_closed_and_readonly_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/ClosedReadonlyClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_enum_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/TestEnum.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::ENUM,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_unknown_then_parse_to_empty_class_data(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/unknown.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(MetadataFactory::unknown(), $classData);
    }

    /**
     * Here we want to make sure that we don't accidentally find any "class" string matches.
     *
     * For example, config files may have class mappings such as `TestEnum::class` which the
     * `::class` part would be picked up by the parser.
     *
     * We only ever want to find class definitions at the start of the file somewhere.
     */
    public function test_given_a_config_file_with_class_mappings_then_identify_as_unknown(): void
    {
        $file = __DIR__ . '/_Fixtures/Parsing/mappings.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(MetadataFactory::unknown(), $classData);
    }
}
