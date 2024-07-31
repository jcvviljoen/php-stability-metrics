<?php

namespace Stability\Tests\Unit\Config;

use Stability\Config\Config;
use Stability\Config\Module;
use Stability\Exception\InvalidConfigurationException;
use Stability\Tests\ExpectThrows;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    use ExpectThrows;

    public function test_from(): void
    {
        $config = [
            'basePath' => 'base',
            'default' => [
                'Some/Default/Namespace',
            ],
            'modules' => [
                [
                    'module' => 'module',
                    'exclude' => ['tests'],
                ],
            ],
        ];

        $config = Config::from($config);

        $this->assertEquals('base', $config->basePath);
        $this->assertEquals(['Some/Default/Namespace'], $config->default);
        $this->assertEquals(
            [new Module('module', 'base/module', ['tests'])],
            $config->modules,
        );
    }

    public function test_from_throws_exception_when_base_path_is_missing(): void
    {
        $config = [
            'default' => [
                'Some/Default/Namespace',
            ],
            'modules' => [
                [
                    'module' => '/module',
                    'exclude' => ['tests'],
                ],
            ],
        ];

        $exception = $this->expectThrows(fn() => Config::from($config));

        $this->assertEquals(InvalidConfigurationException::onMissingBasePath(), $exception);
    }

    public function test_from_throws_exception_when_modules_is_missing(): void
    {
        $config = [
            'basePath' => 'base',
            'default' => [
                'Some/Default/Namespace',
            ],
        ];

        $exception = $this->expectThrows(fn() => Config::from($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModules(), $exception);
    }

    public function test_from_throws_exception_when_module_is_missing(): void
    {
        $config = [
            'basePath' => 'base',
            'default' => [
                'Some/Default/Namespace',
            ],
            'modules' => [
                [
                    'exclude' => ['tests'],
                ],
            ],
        ];

        $exception = $this->expectThrows(fn() => Config::from($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModule(), $exception);
    }
}
