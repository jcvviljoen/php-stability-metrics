<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\_TestSrc\Module3;

/**
 * Traits are outside what the parser classifies, so this file exists to prove a run
 * carries on without it rather than stopping.
 */
trait Countable
{
    public function nothing(): void
    {
    }
}
