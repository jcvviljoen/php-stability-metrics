<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\CycleC;

use Stability\Tests\_Fixtures\_TestSrc\CycleB\ClassB;

/**
 * The third member of a knot, so that a run has a group of components to report on rather
 * than a pair. {@see ClassA} reaches back here, which closes the group.
 */
class ClassC
{
    public function b(): ?ClassB
    {
        return null;
    }
}
