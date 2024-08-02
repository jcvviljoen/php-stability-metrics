<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\Module2;

use Stability\Tests\_Fixtures\_TestSrc\Module1\Class1;

readonly class Class2 extends Abstract2
{
    public function __construct(Class1 $class1)
    {
    }
}
