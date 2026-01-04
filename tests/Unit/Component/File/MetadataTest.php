<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\Exception\InvalidMetadataException;
use Stability\Component\File\Metadata;
use Stability\Tests\_Fixtures\Component\MetadataFactory;
use Stability\Tests\ExpectThrows;

class MetadataTest extends TestCase
{
    use ExpectThrows;

    public function test_given_some_metadata_when_namespace_is_empty_then_throw_exception(): void
    {
        $metadata = Metadata::unknown();

        $exception = $this->expectThrows(fn() => $metadata->namespace());

        $this->assertEquals(
            InvalidMetadataException::onMissingNamespace(),
            $exception,
        );
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
