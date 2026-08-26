<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Graph;

use PHPUnit\Framework\TestCase;
use Stability\Graph\Exception\InvalidGraphOptionException;
use Stability\Graph\GraphOption;

class GraphOptionTest extends TestCase
{
    public function test_given_a_supported_renderer_then_the_option_is_resolved(): void
    {
        $this->assertEquals(GraphOption::MERMAID, GraphOption::fromArgument('mermaid'));
        $this->assertEquals(GraphOption::DOT, GraphOption::fromArgument('dot'));
    }

    public function test_given_an_unsupported_renderer_then_throws_with_the_available_renderers(): void
    {
        $this->expectException(InvalidGraphOptionException::class);
        $this->expectExceptionMessage('Unsupported graph renderer "svg". Available renderers: "mermaid", "dot".');

        GraphOption::fromArgument('svg');
    }
}
