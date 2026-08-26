<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use RuntimeException;
use Stability\Component\File\Metadata;
use Stability\Component\File\Type;

/**
 * This parser only allows standard classes to be parsed.
 * It does not support traits or other PHP constructs given its intended usage.
 *
 * A separate parser could be added in the future should their analysis requirements become clear.
 */
readonly class PhpClassFileParser
{
    /**
     * A type declaration at the start of a line, with any leading modifiers.
     */
    private const string TYPE_DECLARATION = '/^(?:(?:final|abstract|readonly)\s+)*(?<keyword>class|interface|enum)\s/';

    /**
     * @param string $filePath The path to the PHP file.
     *
     * @return Metadata The parsed file data.
     * If the file's class type cannot be determined, the result should contain FileType::UNKNOWN.
     * This allows the user to filter out files that are not relevant to the analysis.
     *
     * @throws RuntimeException
     */
    public function parse(string $filePath): Metadata
    {
        $type = Type::UNKNOWN;
        $namespace = '';
        $imports = [];

        // Open the file for reading
        $file = @fopen($filePath, 'r')
            ?: throw new RuntimeException("Could not open file \"$filePath\" for reading.");

        while (($line = fgets($file)) !== false) {
            if (str_starts_with($line, 'use ')) {
                $imports[] = str_replace('use ', '', rtrim($line, ";\n"));

                continue;
            }

            if (str_starts_with($line, 'namespace ')) {
                $namespace = str_replace('namespace ', '', rtrim($line, ";\n"));

                continue;
            }

            $declared = $this->declaredType($line);

            if (null !== $declared) {
                $type = $declared;

                break;
            }

            // We don't need to read the file past the class definition.
            if (str_starts_with($line, '{') || str_ends_with($line, '{')) {
                break;
            }
        }

        fclose($file);

        if ($type === Type::UNKNOWN) {
            return Metadata::unknown();
        }

        return new Metadata($type, $namespace, $imports);
    }

    /**
     * The type a line declares, or null when it declares nothing.
     *
     * Modifiers are matched in any order and any combination, so "abstract readonly class"
     * is recognised as readily as "abstract class". Matching the declaration itself also
     * keeps prose out of it: a docblock mentioning abstract classes above a concrete class
     * used to be enough to have the file counted as abstract.
     *
     * Traits are deliberately left out, as noted above.
     */
    private function declaredType(string $line): ?Type
    {
        if (1 !== preg_match(self::TYPE_DECLARATION, $line, $declaration)) {
            return null;
        }

        return match ($declaration['keyword']) {
            'class' => str_contains($declaration[0], 'abstract')
                ? Type::ABSTRACT_CLASS
                : Type::CONCRETE_CLASS,
            'interface' => Type::INTERFACE,
            'enum' => Type::ENUM,
        };
    }
}
