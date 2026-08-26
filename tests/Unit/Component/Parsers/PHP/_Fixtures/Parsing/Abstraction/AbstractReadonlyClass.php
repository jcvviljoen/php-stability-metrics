<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP\_Fixtures\Parsing\Abstraction;

/**
 * Declared with two modifiers, in an order the parser used to miss. The mention of
 * abstract classes in this very docblock is also deliberate.
 */
abstract readonly class AbstractReadonlyClass
{
}
