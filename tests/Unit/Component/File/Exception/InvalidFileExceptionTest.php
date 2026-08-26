<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\Exception\InvalidFileException;

class InvalidFileExceptionTest extends TestCase
{
    public function test_on_invalid_file_type(): void
    {
        $exception = InvalidFileException::onInvalidFileType('file.php');

        $this->assertEquals(
            'The file type of file "file.php" could not be determined. Perhaps it should be excluded?',
            $exception->getMessage(),
        );
    }

    public function test_on_unreadable_file(): void
    {
        $exception = InvalidFileException::onUnreadableFile('some/file.php');

        self::assertSame('The file "some/file.php" could not be opened for reading.', $exception->getMessage());
    }
}
