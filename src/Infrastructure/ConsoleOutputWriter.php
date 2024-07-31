<?php

namespace Stability\Infrastructure;

use Stability\OutputWriter;
use Stability\StabilityResult;
use Override;
use Symfony\Component\Console\Output\ConsoleOutput;

readonly class ConsoleOutputWriter implements OutputWriter
{
    private function __construct(private ConsoleOutput $console)
    {
    }

    #[Override] public function outputResult(StabilityResult $result): void
    {
        foreach ($result->componentResults as $componentResult) {
            $this->console->writeln([
                '----------------------------------------',
                "Component: {$componentResult->component->name}",
                '----------------------------------------',
                "| Abstractness: $componentResult->abstractness",
                "| Instability: $componentResult->instability",
                "| DMS: $componentResult->dms",
                '----------------------------------------',
                '',
            ]);
        }
    }

    public static function create(): self
    {
        return new self(new ConsoleOutput());
    }
}
