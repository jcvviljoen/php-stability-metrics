<?php

namespace Instability;

interface OutputFormatter
{
    public function outputResult(StabilityResult $result): void;
}
