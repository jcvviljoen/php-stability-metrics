<?php

declare(strict_types=1);

namespace Stability\Output;

use Stability\Metric\Result;

interface OutputWriter
{
    public function outputResult(Result $result): void;
}
