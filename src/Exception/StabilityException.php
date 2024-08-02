<?php

declare(strict_types=1);

namespace Stability\Exception;

use Exception;

abstract class StabilityException extends Exception
{
    protected function __construct(string $message)
    {
        parent::__construct($message);
    }
}
