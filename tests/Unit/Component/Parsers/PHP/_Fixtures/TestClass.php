<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures;

use RuntimeException;
use Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction\TestAbstractClass;
use Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction\TestInterface;

final class TestClass extends TestAbstractClass implements TestInterface
{
    public function empty(TestEnum $enum): void
    {
        throw new RuntimeException('Not implemented');
    }
}
