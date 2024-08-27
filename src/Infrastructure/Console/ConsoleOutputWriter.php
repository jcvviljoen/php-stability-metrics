<?php

declare(strict_types=1);

namespace Stability\Infrastructure\Console;

use Override;
use Stability\OutputWriter;
use Stability\StabilityResult;
use Symfony\Component\Console\Output\ConsoleOutput;

readonly class ConsoleOutputWriter implements OutputWriter
{
    public function __construct(private ConsoleOutput $console)
    {
    }

    #[Override] public function outputResult(StabilityResult $result): void
    {
        foreach ($result->componentResults as $componentResult) {
            $zoneDescription = $componentResult->zone->description();
            $zoneIcon = $componentResult->zone->icon();

            $this->console->writeln([
                '----------------------------------------',
                "Component: {$componentResult->component->module->name}",
                '----------------------------------------',
                "| Abstractness: {$componentResult->abstractness()}",
                "| Instability: {$componentResult->instability()}",
                "| DMS: {$componentResult->dms()}",
                "| Zone: $zoneIcon $zoneDescription",
                '----------------------------------------',
                '',
            ]);
        }
    }
}
