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
}
