<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Loaders;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Config\ConfigType;
use Stability\Config\Loaders\ConfigLoaderFactory;
use Stability\Config\Loaders\PHP\PhpArrayConfigLoader;

class ConfigLoaderFactoryTest extends TestCase
{
    #[DataProvider('provide_config_types')]
    public function test_create_config_loader(ConfigType $type, string $expected): void
    {
        $loader = ConfigLoaderFactory::create($type);

        $this->assertInstanceOf($expected, $loader);
    }

    /**
     * @return array<string, array{type: ConfigType, expected: class-string}>
     */
    public static function provide_config_types(): array
    {
        return [
            'Given a PHP array config type, then provide the PHP array config loader' => [
                'type' => ConfigType::PHP_ARRAY,
                'expected' => PhpArrayConfigLoader::class,
            ],
        ];
    }
}
