<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\Abstraction;

use Stability\Tests\Unit\Infrastructure\PHP\_Fixtures\Parser\TestEnum;

interface TestInterface
{
    public function empty(TestEnum $enum): void;
}
