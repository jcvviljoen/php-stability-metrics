<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\Module1;

use Stability\Tests\_Fixtures\_TestSrc\Module3\Class3;

readonly class Class1 extends Abstract1 implements Interface1
{
    public function __construct(Class3 $class3)
    {
    }
}
