<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing;

use Override;
use RuntimeException;
use Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction\TestAbstractClass;
use Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction\TestInterface;

final class TestClass extends TestAbstractClass implements TestInterface
{
    #[Override]
    public function empty(TestEnum $enum): void
    {
        throw new RuntimeException('Not implemented');
    }
}
