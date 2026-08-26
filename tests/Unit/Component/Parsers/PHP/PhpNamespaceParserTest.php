<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers\PHP;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\Parsers\PHP\PhpNamespaceParser;
use Stability\Tests\ExpectThrows;

class PhpNamespaceParserTest extends TestCase
{
    use ExpectThrows;

    private PhpNamespaceParser $parser;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new PhpNamespaceParser();
    }

    public function test_given_an_empty_list_of_namespaces_then_returns_empty_string(): void
    {
        $result = $this->parser->primaryNamespace([]);

        $this->assertSame('', $result);
    }

    public function test_given_a_single_namespace_then_returns_that_namespace(): void
    {
        $result = $this->parser->primaryNamespace(['App\Controllers']);

        $this->assertSame('App\Controllers', $result);
    }

    public function test_given_multiple_namespaces_with_common_prefix_then_return_the_common_prefix(): void
    {
        $result = $this->parser->primaryNamespace([
            'App\Domain\Money',
            'App\Domain\User',
            'App\Domain\User\Profile',
            'App\Domain\Order',
        ]);

        $this->assertSame('App\Domain', $result);
    }

    public function test_given_multiple_namespaces_without_common_prefix_then_throws_exception(): void
    {
        $namespaces = [
            'App\Domain\Money',
            'Wrong\Services',
            'App\Controllers',
        ];

        $exception = $this->expectThrows(fn() => $this->parser->primaryNamespace($namespaces));

        $this->assertEquals(
            InvalidComponentException::onMismatchedNamespace('App\Domain\Money', ''),
            $exception,
        );
    }
}
