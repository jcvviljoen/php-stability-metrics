<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Output\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Output\Exception\UnwritableResultException;

class UnwritableResultExceptionTest extends TestCase
{
    public function test_on_failed_encoding(): void
    {
        $exception = UnwritableResultException::onFailedEncoding();

        self::assertSame('The analysis results could not be encoded to JSON.', $exception->getMessage());
    }

    public function test_on_failed_write(): void
    {
        $exception = UnwritableResultException::onFailedWrite('some/path.json');

        self::assertSame(
            'Could not write the analysis results to "some/path.json". '
            . 'Check that the directory exists and is writable.',
            $exception->getMessage(),
        );
    }
}
