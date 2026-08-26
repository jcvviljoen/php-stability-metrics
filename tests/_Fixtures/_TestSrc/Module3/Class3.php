<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\Module3;

readonly class Class3
{
    use Countable;

    public function name(): string
    {
        return self::class;
    }
}
