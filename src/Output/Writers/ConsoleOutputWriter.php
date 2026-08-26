<?php

declare(strict_types=1);

namespace Stability\Output\Writers;

use Override;
use Stability\Metric\Result;
use Stability\Output\OutputWriter;
use Symfony\Component\Console\Output\OutputInterface;

readonly class ConsoleOutputWriter implements OutputWriter
{
    public function __construct(private OutputInterface $console)
    {
    }

    #[Override] public function outputResult(Result $result): void
    {
        foreach ($result->stableDependencyMetrics as $componentResult) {
            $zoneDescription = $componentResult->zone->description();
            $zoneIcon = $componentResult->zone->icon();

            $this->console->writeln([
                '----------------------------------------',
                "Component: {$componentResult->componentName}",
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
