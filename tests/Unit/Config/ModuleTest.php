<?php

namespace Instability\Tests\Unit\Config;

use Instability\Config\Module;
use Instability\Exception\InvalidModuleException;
use Instability\Tests\ExpectThrows;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    use ExpectThrows;

    private const string DIRECTORY_DATA = __DIR__ . '/../../_Fixtures/Data';

    public function test_files(): void
    {
        $module = new Module(self::DIRECTORY_DATA, []);

        $files = $module->files();

        $this->assertEquals(
            [
                realpath(self::DIRECTORY_DATA . '/TestClass.php'),
                realpath(self::DIRECTORY_DATA . '/Embedded/TestAbstractClass.php'),
                realpath(self::DIRECTORY_DATA . '/Embedded/Testable.php'),
            ],
            $files,
        );
    }

    public function test_given_a_module_when_reading_files_in_missing_directory_then_throw_exception(): void
    {
        $path = self::DIRECTORY_DATA . '/non-existent';
        $module = new Module($path, []);

        $exception = $this->expectThrows(fn() => $module->files());

        $this->assertEquals(InvalidModuleException::onInvalidModulePath($path), $exception);
    }
}
