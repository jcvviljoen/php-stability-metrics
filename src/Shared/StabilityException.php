<?php

declare(strict_types=1);

namespace Stability\Shared;

use Exception;
use Throwable;

/**
 * The exception every other one here extends, which is how the CLI can catch anything this
 * tool raises on purpose and report it as a message rather than a fatal.
 *
 * It sits in a component of its own because every other component needs it. Putting it in
 * any of them would have all the others depend on that one, and would close a cycle the
 * first time that component needed to raise an error itself.
 */
abstract class StabilityException extends Exception
{
    protected function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
