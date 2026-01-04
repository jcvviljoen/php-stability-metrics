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
        $file = __DIR__ . '/_Fixtures/Abstraction/TestAbstractClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::ABSTRACT_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_interface_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/Abstraction/TestInterface.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::INTERFACE,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction',
                ['Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\TestEnum'],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_concrete_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/TestClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures',
                [
                    'RuntimeException',
                    'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction\TestAbstractClass',
                    'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction\TestInterface',
                ],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_closed_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/ClosedClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_readonly_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/ReadonlyClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_class_is_closed_and_readonly_then_parse_to_concrete_class(): void
    {
        $file = __DIR__ . '/_Fixtures/ClosedReadonlyClass.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::CONCRETE_CLASS,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_enum_class_then_parse(): void
    {
        $file = __DIR__ . '/_Fixtures/TestEnum.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(
            new Metadata(
                Type::ENUM,
                'Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures',
                [],
            ),
            $classData,
        );
    }

    public function test_given_a_path_when_file_is_unknown_then_parse_to_empty_class_data(): void
    {
        $file = __DIR__ . '/_Fixtures/unknown.php';

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
        $file = __DIR__ . '/_Fixtures/mappings.php';

        $classData = $this->parser->parse($file);

        $this->assertEquals(MetadataFactory::unknown(), $classData);
    }
}
