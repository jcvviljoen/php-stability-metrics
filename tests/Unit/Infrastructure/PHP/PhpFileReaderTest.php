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
        $this->markTestSkipped('To be implemented');
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
