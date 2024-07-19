<?php

namespace Instability\Exception;

use Exception;

abstract class InstabilityException extends Exception
{
    /**
     * @return array<string>
     */
    abstract public function output(): array;
}
