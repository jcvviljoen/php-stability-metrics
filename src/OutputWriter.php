<?php

namespace Stability;

interface OutputWriter
{
    public function outputResult(StabilityResult $result): void;
}
