<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Output;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Output\OutputOption;
use Stability\Output\OutputWriterFactory;
use Stability\Output\Writers\ConsoleOutputWriter;
use Stability\Output\Writers\JsonOutputWriter;

class OutputWriterFactoryTest extends TestCase
{
    #[DataProvider('provide_output_options')]
    public function test_create_output_writer(OutputOption $option, string $expected): void
    {
        $loader = OutputWriterFactory::create($option->value, null);

        $this->assertInstanceOf($expected, $loader);
    }

    /**
     * @return array<string, array{option: OutputOption, expected: class-string}>
     */
    public static function provide_output_options(): array
    {
        return [
            'When given a "console" output option, then provide console writer' => [
                'option' => OutputOption::CONSOLE,
                'expected' => ConsoleOutputWriter::class,
            ],
            'When given a "json" output option, then provide json writer' => [
                'option' => OutputOption::JSON,
                'expected' => JsonOutputWriter::class,
            ],
        ];
    }
}
