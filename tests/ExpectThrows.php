<?php

declare(strict_types=1);

namespace Instability\Tests;

use PHPUnit\Framework\AssertionFailedError;
use Throwable;

trait ExpectThrows
{
    public function expectThrows(callable $action): Throwable
    {
        try {
            $action();
        } catch (Throwable $exception) {
            return $exception;
        }

        throw new AssertionFailedError('Failed asserting that an exception was thrown');
    }
}
