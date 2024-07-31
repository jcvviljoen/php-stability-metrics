<?php

namespace Instability;

interface OutputWriter
{
    public function outputResult(StabilityResult $result): void;
}
