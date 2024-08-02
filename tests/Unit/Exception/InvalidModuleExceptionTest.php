<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Exception\InvalidModuleException;

class InvalidModuleExceptionTest extends TestCase
{
    public function test_on_invalid_module_path(): void
    {
        $exception = InvalidModuleException::onInvalidModulePath('path');

        self::assertSame('Module directory "path" not found.', $exception->getMessage());
    }
}
