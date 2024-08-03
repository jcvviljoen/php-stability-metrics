<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP;

use PHPUnit\Framework\TestCase;
use Stability\Exception\InvalidModuleException;
use Stability\Infrastructure\PHP\PhpFileReader;
use Stability\Tests\ExpectThrows;

class PhpFileReaderTest extends TestCase
{
    use ExpectThrows;

    private PhpFileReader $phpFileReader;

    protected function setUp(): void
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

        $files = $this->phpFileReader->files($relativeDirectoryPath);

        $this->assertEquals(
            [
                __DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/config_missing_base_path.php',
                __DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/config_missing_module.php',
                __DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/config_missing_modules.php',
                __DIR__ . DIRECTORY_SEPARATOR . '_Fixtures/Files/config_valid.php',
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
            fn() => $this->phpFileReader->files($relativeDirectoryPath),
        );

        $this->assertEquals(
            InvalidModuleException::onInvalidModulePath($relativeDirectoryPath),
            $exception,
        );
    }
}
