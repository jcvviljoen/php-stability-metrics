<?php

declare(strict_types=1);

namespace Stability\Output;

use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;
use Stability\Output\Writers\ConsoleOutputWriter;
use Stability\Output\Writers\JsonOutputWriter;
use Symfony\Component\Console\Output\OutputInterface;

readonly class OutputWriterFactory
{
    public function __construct(private OutputInterface $console)
    {
    }

    public function create(OutputSetting $setting): OutputWriter
    {
        return match ($setting->option) {
            OutputOption::CONSOLE => new ConsoleOutputWriter($this->console),
            OutputOption::JSON => new JsonOutputWriter($setting),
        };
    }
}
