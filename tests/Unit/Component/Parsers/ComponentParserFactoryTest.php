<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\Parsers;

use PHPUnit\Framework\TestCase;
use Stability\Component\Parsers\ComponentParserFactory;
use Stability\Component\Parsers\PHP\PhpClassFileParser;
use Stability\Component\Parsers\PHP\PhpComponentParser;
use Stability\Component\Parsers\PHP\PhpFileReader;
use Stability\Component\Parsers\PHP\PhpNamespaceParser;

class ComponentParserFactoryTest extends TestCase
{
    public function test_create_php_component_parser(): void
    {
        $componentParser = ComponentParserFactory::create();

        $this->assertEquals(
            new PhpComponentParser(
                new PhpClassFileParser(),
                new PhpFileReader(),
                new PhpNamespaceParser(),
            ),
            $componentParser,
        );
    }
}
