<?php

declare(strict_types=1);

namespace Stability\Infrastructure\Output;

use Override;
use RuntimeException;
use Stability\OutputWriter;
use Stability\StabilityResult;

readonly class JsonOutputWriter implements OutputWriter
{
    public function __construct(private string $filePath)
    {
    }

    #[Override] public function outputResult(StabilityResult $result): void
    {
        $json = json_encode($result, JSON_PRETTY_PRINT);

        if (false === $json) {
            throw new RuntimeException('Failed to encode result to JSON');
        }

        file_put_contents($this->filePath, $json);
    }
}
