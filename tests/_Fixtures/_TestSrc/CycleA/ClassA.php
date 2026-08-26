<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\CycleA;

use Stability\Tests\_Fixtures\_TestSrc\CycleB\ClassB;
use Stability\Tests\_Fixtures\_TestSrc\CycleC\ClassC;

/**
 * Depends on CycleB, which depends straight back. Exists so that a full run has a
 * circular dependency to report on.
 *
 * It reaches CycleC as well, which reaches CycleB, so a configuration naming all three
 * has a knot rather than a pair. A configuration naming only A and B still has the pair,
 * because an import of something outside every configured component counts for nothing.
 */
class ClassA
{
    public function b(): ?ClassB
    {
        return null;
    }

    public function c(): ?ClassC
    {
        return null;
    }
}
