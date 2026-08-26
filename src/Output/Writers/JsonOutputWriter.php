<?php

declare(strict_types=1);

namespace Stability\Output\Writers;

use Override;
use RuntimeException;
use Stability\Config\OutputSetting;
use Stability\Metric\Result;
use Stability\Output\OutputWriter;

readonly class JsonOutputWriter implements OutputWriter
{
    public function __construct(private OutputSetting $settings)
    {
    }

    #[Override] public function outputResult(Result $result): void
    {
        $json = json_encode($result, JSON_PRETTY_PRINT);

        if (false === $json) {
            throw new RuntimeException('Failed to encode result to JSON');
        }

        file_put_contents($this->settings->fullFilePath('json'), $json);
    }
}
