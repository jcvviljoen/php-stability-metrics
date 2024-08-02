<?php

declare(strict_types=1);

namespace Stability;

interface OutputWriter
{
    public function outputResult(StabilityResult $result): void;
}
