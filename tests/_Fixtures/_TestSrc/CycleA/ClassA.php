<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\CycleA;

use Stability\Tests\_Fixtures\_TestSrc\CycleB\ClassB;

/**
 * Depends on CycleB, which depends straight back. Exists so that a full run has a
 * circular dependency to report on.
 */
class ClassA
{
    public function b(): ?ClassB
    {
        return null;
    }
}
