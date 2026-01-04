<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Component\Exception\InvalidComponentException;

class InvalidComponentExceptionTest extends TestCase
{
    public function test_on_invalid_module_path(): void
    {
        $exception = InvalidComponentException::onInvalidModulePath('path');

        self::assertSame('Component\'s module directory "path" could not be found.', $exception->getMessage());
    }

    public function test_on_invalid_component_path(): void
    {
        $exception = InvalidComponentException::onEmptyComponent('ModuleName');

        self::assertSame('Component for module "ModuleName" contains no PHP files.', $exception->getMessage());
    }
}
