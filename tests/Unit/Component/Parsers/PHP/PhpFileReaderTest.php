<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\Parsers\PHP\PhpFileReader;
use Stability\Tests\ExpectThrows;

class PhpFileReaderTest extends TestCase
{
    use ExpectThrows;

    private PhpFileReader $phpFileReader;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->phpFileReader = new PhpFileReader();
    }

    public function test_given_a_relative_path_when_files_are_php_files_then_return_files(): void
    {
        /**
         * Although the reader specifies that the path is relative to the base path to be analysed,
         * the path in the test is relative to the current directory.
         * This is necessary because the test is executed from the root of the project,
         * and the fixtures are in a subdirectory from this test.
         */
        $relativeDirectoryPath = __DIR__ . '/_Fixtures/Files';
        $allFiles = array_merge(
            glob($relativeDirectoryPath . DIRECTORY_SEPARATOR . '*.*'), // Ignore directories
            glob($relativeDirectoryPath . DIRECTORY_SEPARATOR . 'Nested' . DIRECTORY_SEPARATOR . '*'),
        );

        $files = $this->phpFileReader->files($relativeDirectoryPath, []);

        $this->assertEquals(
            [
                __DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/Nested/Nest.php',
            ],
            $files,
        );
        $this->assertEquals(
            [__DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/Invalid.txt'],
            array_diff($allFiles, $files),
        );
    }

    public function test_given_a_relative_path_when_directory_cannot_be_found_then_throw_exception(): void
    {
        $relativeDirectoryPath = 'path/to/non/existent/directory';

        $exception = $this->expectThrows(
            fn() => $this->phpFileReader->files($relativeDirectoryPath, []),
        );

        $this->assertEquals(
            InvalidComponentException::onInvalidModulePath($relativeDirectoryPath),
            $exception,
        );
    }

    public function test_given_a_relative_path_when_excluding_files_then_return_filtered_files(): void
    {
        $relativeDirectoryPath = __DIR__ . '/_Fixtures/Files';

        $files = $this->phpFileReader->files(
            $relativeDirectoryPath,
            [
                '_Fixtures/Files/Nested/Nest.php',
            ],
        );

        $this->assertEmpty($files);
    }
}
