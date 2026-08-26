<?php

declare(strict_types=1);

namespace Stability;

use Exception;
use Throwable;

abstract class StabilityException extends Exception
{
    protected function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
