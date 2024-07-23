<?php

namespace Instability\Exception;

use Exception;

abstract class StabilityException extends Exception
{
    protected function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @return array<string>
     */
    abstract public function output(): array;
}