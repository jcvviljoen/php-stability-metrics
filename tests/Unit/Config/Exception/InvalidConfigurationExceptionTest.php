<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;

class InvalidConfigurationExceptionTest extends TestCase
{
    public function test_on_missing_config_file(): void
    {
        $exception = InvalidConfigurationException::onMissingConfigFile('path/to/config');

        $this->assertEquals(
            'No configuration file found at "path/to/config".',
            $exception->getMessage(),
        );
    }

    public function test_on_missing_modules(): void
    {
        $exception = InvalidConfigurationException::onMissingModules();

        $this->assertEquals(
            'Configuration has no "modules" to run against.',
            $exception->getMessage(),
        );
    }

    public function test_on_duplicate_module_name(): void
    {
        $exception = InvalidConfigurationException::onDuplicateModuleName('UserModule');

        $this->assertEquals(
            'Duplicate module name found: "UserModule".',
            $exception->getMessage(),
        );
    }
}
