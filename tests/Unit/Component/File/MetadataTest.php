<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\Exception\InvalidMetadataException;
use Stability\Component\File\Metadata;
use Stability\Component\File\Type;
use Stability\Tests\_Fixtures\Component\MetadataFactory;
use Stability\Tests\ExpectThrows;

class MetadataTest extends TestCase
{
    use ExpectThrows;

    public function test_given_a_classified_file_with_no_namespace_then_the_metadata_cannot_be_built(): void
    {
        $exception = $this->expectThrows(fn() => new Metadata(Type::CONCRETE_CLASS, '', []));

        $this->assertEquals(
            InvalidMetadataException::onMissingNamespace(),
            $exception,
        );
    }

    public function test_given_an_unclassified_file_then_it_needs_no_namespace(): void
    {
        $metadata = Metadata::unknown();

        $this->assertSame('', $metadata->namespace());
    }

    public function test_given_some_metadata_when_namespace_is_set_then_return_namespace(): void
    {
        $metadata = MetadataFactory::abstract1();

        $this->assertEquals('Stability\Tests\_Fixtures\_TestSrc\Module1', $metadata->namespace());
    }

    public function test_when_creating_unknown_metadata_then_return_metadata_with_unknown_instance(): void
    {
        $metadata = Metadata::unknown();

        $this->assertEquals(MetadataFactory::unknown(), $metadata);
    }
}
