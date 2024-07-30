<?php

namespace Instability\Infrastructure;

use Instability\OutputFormatter;
use Instability\StabilityResult;
use Override;

readonly class ConsoleOutputFormatter implements OutputFormatter
{
    #[Override] public function outputResult(StabilityResult $result): void
    {
        foreach ($result->componentResults as $componentResult) {
            echo "----------------------------------------\n";
            echo "Component: {$componentResult->component->name}\n";
            echo "----------------------------------------\n";

            echo "| Abstractness: $componentResult->abstractness\n";
            echo "| Instability: $componentResult->instability\n";
            echo "| DMS: $componentResult->dms\n";

            echo "----------------------------------------\n";
            echo "\n";
        }
    }
}
