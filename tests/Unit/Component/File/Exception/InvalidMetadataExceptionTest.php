<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\Exception\InvalidMetadataException;

class InvalidMetadataExceptionTest extends TestCase
{
    public function test_on_missing_namespace(): void
    {
        $exception = InvalidMetadataException::onMissingNamespace();

        $this->assertEquals('Metadata is missing a namespace declaration.', $exception->getMessage());
    }
}
