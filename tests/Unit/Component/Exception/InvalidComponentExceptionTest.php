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

    public function test_on_threshold_out_of_range(): void
    {
        $exception = InvalidComponentException::onThresholdOutOfRange('thresholdZoneOfPain', 1.5);

        self::assertSame(
            'Threshold "thresholdZoneOfPain" must be a distance between 0 and 1, but was 1.5.',
            $exception->getMessage(),
        );
    }

    public function test_on_invalid_component_path(): void
    {
        $exception = InvalidComponentException::onEmptyComponent('ModuleName');

        self::assertSame('Component for module "ModuleName" contains no PHP files.', $exception->getMessage());
    }

    public function test_on_mismatched_namespace(): void
    {
        $exception = InvalidComponentException::onMismatchedNamespace('App\Other', 'App\Domain');

        self::assertSame(
            'Namespace "App\Other" does not sit under the component\'s primary namespace "App\Domain".',
            $exception->getMessage(),
        );
    }
}
