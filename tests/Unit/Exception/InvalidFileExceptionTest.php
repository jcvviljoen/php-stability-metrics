<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Component\Class\ClassType;
use Stability\Exception\InvalidFileException;

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

    public function test_on_unsupported_file_type(): void
    {
        $exception = InvalidFileException::onUnsupportedFileType('file.php', ClassType::INTERFACE);

        $this->assertEquals(
            'Unsupported file type "INTERFACE" found for file "file.php".',
            $exception->getMessage(),
        );
    }
}
