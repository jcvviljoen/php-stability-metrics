<?php

declare(strict_types=1);

namespace Stability\Output\Writers;

use Override;
use Stability\Config\OutputSetting;
use Stability\Metric\Result;
use Stability\Output\Exception\UnwritableResultException;
use Stability\Output\OutputWriter;

readonly class JsonOutputWriter implements OutputWriter
{
    public function __construct(private OutputSetting $settings)
    {
    }

    /**
     * @throws UnwritableResultException
     */
    #[Override] public function outputResult(Result $result): void
    {
        $json = json_encode($result, JSON_PRETTY_PRINT);

        if (false === $json) {
            throw UnwritableResultException::onFailedEncoding();
        }

        $path = $this->settings->fullFilePath('json');

        if (false === @file_put_contents($path, $json)) {
            throw UnwritableResultException::onFailedWrite($path);
        }
    }
}
