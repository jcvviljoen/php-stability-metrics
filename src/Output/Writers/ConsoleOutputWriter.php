<?php

declare(strict_types=1);

namespace Stability\Output\Writers;

use Override;
use Stability\Metric\Result;
use Stability\Output\OutputWriter;
use Symfony\Component\Console\Output\OutputInterface;

readonly class ConsoleOutputWriter implements OutputWriter
{
    /**
     * Two decimal places is enough to place a component against the main sequence by eye.
     */
    private const int PRECISION = 2;

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
                '| Abstractness: ' . $this->format($componentResult->abstractness()),
                '| Instability: ' . $this->format($componentResult->instability()),
                '| DMS: ' . $this->format($componentResult->dms()),
                "| Zone: $zoneIcon $zoneDescription",
                '----------------------------------------',
                '',
            ]);
        }
    }

    private function format(float $value): string
    {
        return number_format($value, self::PRECISION);
    }
}
