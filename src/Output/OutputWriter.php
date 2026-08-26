<?php

declare(strict_types=1);

namespace Stability\Output;

use Stability\Metric\Result;
use Stability\Output\Exception\UnwritableResultException;

interface OutputWriter
{
    /**
     * @throws UnwritableResultException
     */
    public function outputResult(Result $result): void;
}
