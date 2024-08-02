<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Config\ConfigLoaderFactory;
use Stability\Config\ConfigType;
use Stability\Infrastructure\PHP\PhpConfigLoader;

class ConfigLoaderFactoryTest extends TestCase
{
    #[DataProvider('provide_config_types')]
    public function test_create_config_loader(ConfigType $type, string $expected): void
    {
        $file = "config.$type->value";

        $loader = ConfigLoaderFactory::create($file);

        $this->assertInstanceOf($expected, $loader);
    }

    /**
     * @return array<string, array{type: ConfigType, expected: class-string}>
     */
    public static function provide_config_types(): array
    {
        return [
            'When given a php config file, then provide the PHP config loader' => [
                'type' => ConfigType::PHP,
                'expected' => PhpConfigLoader::class,
            ],
        ];
    }
}
