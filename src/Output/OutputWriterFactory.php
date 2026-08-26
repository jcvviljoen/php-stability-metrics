<?php

declare(strict_types=1);

namespace Stability\Output;

use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;
use Stability\Output\Writers\ConsoleOutputWriter;
use Stability\Output\Writers\JsonOutputWriter;
use Symfony\Component\Console\Output\ConsoleOutput;

readonly class OutputWriterFactory
{
    public static function create(OutputSetting $setting): OutputWriter
    {
        return match ($setting->option) {
            OutputOption::CONSOLE => new ConsoleOutputWriter(new ConsoleOutput()),
            OutputOption::JSON => new JsonOutputWriter($setting),
        };
    }
}
