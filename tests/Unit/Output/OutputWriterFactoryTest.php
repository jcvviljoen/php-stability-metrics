<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Output;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Output\OutputSetting;
use Stability\Output\OutputWriterFactory;
use Stability\Output\Writers\ConsoleOutputWriter;
use Stability\Output\Writers\JsonOutputWriter;
use Stability\Tests\_Fixtures\Output\OutputSettingFactory;

class OutputWriterFactoryTest extends TestCase
{
    #[DataProvider('provide_output_settings')]
    public function test_create_output_writer(OutputSetting $setting, string $expected): void
    {
        $loader = OutputWriterFactory::create($setting);

        $this->assertInstanceOf($expected, $loader);
    }

    /**
     * @return array<string, array{setting: OutputSetting, expected: class-string}>
     */
    public static function provide_output_settings(): array
    {
        return [
            'When given a "console" output option, then provide console writer' => [
                'setting' => OutputSettingFactory::console(),
                'expected' => ConsoleOutputWriter::class,
            ],
            'When given a "json" output option, then provide json writer' => [
                'setting' => OutputSettingFactory::json(),
                'expected' => JsonOutputWriter::class,
            ],
        ];
    }
}
