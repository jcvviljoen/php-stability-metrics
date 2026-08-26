<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Output;

use PHPUnit\Framework\TestCase;
use Stability\Output\Exception\InvalidOutputOptionException;
use Stability\Output\OutputOption;

class OutputOptionTest extends TestCase
{
    public function test_given_a_supported_option_then_it_is_resolved(): void
    {
        $this->assertEquals(OutputOption::CONSOLE, OutputOption::fromArgument('console'));
        $this->assertEquals(OutputOption::JSON, OutputOption::fromArgument('json'));
    }

    public function test_given_an_unsupported_option_then_throws_with_the_available_options(): void
    {
        $this->expectException(InvalidOutputOptionException::class);
        $this->expectExceptionMessage('Unsupported output option "yaml". Available options: "console", "json".');

        OutputOption::fromArgument('yaml');
    }
}
