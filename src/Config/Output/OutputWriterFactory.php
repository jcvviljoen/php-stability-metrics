<?php

declare(strict_types=1);

namespace Stability\Config\Output;

use Stability\Infrastructure\Output\ConsoleOutputWriter;
use Stability\Infrastructure\Output\JsonOutputWriter;
use Stability\OutputWriter;
use Symfony\Component\Console\Output\ConsoleOutput;

readonly class OutputWriterFactory
{
    private const string DEFAULT_FILE_NAME = 'stability-result';

    public static function create(string $option, ?string $filePath): OutputWriter
    {
        return match (OutputOption::from($option)) {
            OutputOption::CONSOLE => new ConsoleOutputWriter(new ConsoleOutput()),
            OutputOption::JSON => new JsonOutputWriter($filePath ?? self::DEFAULT_FILE_NAME . '.json'),
        };
    }
}
