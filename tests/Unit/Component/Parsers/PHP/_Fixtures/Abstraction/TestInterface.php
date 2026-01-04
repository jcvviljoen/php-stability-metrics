<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Abstraction;

use Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\TestEnum;

interface TestInterface
{
    public function empty(TestEnum $enum): void;
}
