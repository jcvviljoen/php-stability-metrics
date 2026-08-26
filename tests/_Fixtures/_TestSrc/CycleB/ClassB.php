<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\CycleB;

use Stability\Tests\_Fixtures\_TestSrc\CycleA\ClassA;

/**
 * The other half of the deliberate cycle described in {@see ClassA}.
 */
class ClassB
{
    public function a(): ?ClassA
    {
        return null;
    }
}
