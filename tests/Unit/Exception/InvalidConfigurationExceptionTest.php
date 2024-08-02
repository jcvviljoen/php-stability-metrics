<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Stability\Exception\InvalidConfigurationException;

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

    public function test_on_missing_base_path(): void
    {
        $exception = InvalidConfigurationException::onMissingBasePath();

        $this->assertEquals(
            'Configuration is missing `base_path` property.',
            $exception->getMessage(),
        );
    }

    public function test_on_missing_modules(): void
    {
        $exception = InvalidConfigurationException::onMissingModules();

        $this->assertEquals(
            'Configuration has no `modules` to run against.',
            $exception->getMessage(),
        );
    }

    public function test_on_missing_module(): void
    {
        $exception = InvalidConfigurationException::onMissingModule();

        $this->assertEquals(
            'Module is missing `module` path property.',
            $exception->getMessage(),
        );
    }
}
