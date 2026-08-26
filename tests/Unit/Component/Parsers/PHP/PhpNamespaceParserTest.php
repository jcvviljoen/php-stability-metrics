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

    /**
     * The prefix has to be narrowed against every namespace, not just up to the first one
     * that diverges. Stopping early leaves a prefix that later namespaces do not sit
     * under, which used to be reported as a mismatch in the component rather than as
     * what it was: a prefix that had not finished being worked out.
     */
    public function test_given_a_namespace_that_diverges_earlier_than_the_first_then_narrows_the_prefix(): void
    {
        $result = $this->parser->primaryNamespace([
            'Domain\\DataSource\\Tests\\Mocks',
            'Domain\\DataSource\\Tests\\Repositories',
            'Domain\\DataSource\\Repositories',
        ]);

        $this->assertSame('Domain\\DataSource', $result);
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
