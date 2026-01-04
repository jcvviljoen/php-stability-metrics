<?php

declare(strict_types=1);

namespace Stability\Component\Parsers;

use Stability\Component\ComponentParser;
use Stability\Component\Parsers\PHP\PhpClassFileParser;
use Stability\Component\Parsers\PHP\PhpComponentParser;
use Stability\Component\Parsers\PHP\PhpFileReader;
use Stability\Component\Parsers\PHP\PhpNamespaceParser;

readonly class ComponentParserFactory
{
    /**
     * For now this only supports PHP components, but it can always be extended in the future.
     */
    public static function create(): ComponentParser
    {
        return new PhpComponentParser(
            new PhpClassFileParser(),
            new PhpFileReader(),
            new PhpNamespaceParser(),
        );
    }
}
