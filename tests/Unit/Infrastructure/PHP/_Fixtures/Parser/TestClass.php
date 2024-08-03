<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser;

use Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\Abstraction\TestAbstractClass;
use Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\Abstraction\TestInterface;

final class TestClass extends TestAbstractClass implements TestInterface
{
    public function empty(TestEnum $enum): void
    {
        // TODO: Implement empty().
    }
}
