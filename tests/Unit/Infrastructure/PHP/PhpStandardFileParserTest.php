<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP;

use PHPUnit\Framework\TestCase;
use Stability\Infrastructure\PHP\PhpStandardFileParser;

class PhpStandardFileParserTest extends TestCase
{
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
        $this->markTestSkipped('To be implemented');
    }

    public function test_given_a_path_when_file_is_interface_then_parse(): void
    {
        $this->markTestSkipped('To be implemented');
    }

    public function test_given_a_path_when_file_is_concrete_class_then_parse(): void
    {
        $this->markTestSkipped('To be implemented');
    }

    public function test_given_a_path_when_file_is_enum_class_then_parse(): void
    {
        $this->markTestSkipped('To be implemented');
    }

    public function test_given_a_path_when_file_is_unknown_then_parse_to_empty_class_data(): void
    {
        $this->markTestSkipped('To be implemented');
    }

    public function test_given_a_path_when_file_cannot_be_read_then_throw_exception(): void
    {
        $this->markTestSkipped('To be implemented');
    }
}
