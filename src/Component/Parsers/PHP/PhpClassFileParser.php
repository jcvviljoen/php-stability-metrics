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

            if (str_contains($line, 'abstract class')) {
                $type = Type::ABSTRACT_CLASS;

                break;
            }

            if (str_starts_with($line, 'interface')) {
                $type = Type::INTERFACE;

                break;
            }

            if (str_starts_with($line, 'enum')) {
                $type = Type::ENUM;

                break;
            }

            if ($this->isClassDefinition($line)) {
                $type = Type::CONCRETE_CLASS;

                break;
            }

            if (str_starts_with($line, 'namespace ')) {
                $namespace = str_replace('namespace ', '', rtrim($line, ";\n"));

                continue;
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
     * Although not perfect, this method is a simple way to determine if a line is a class definition.
     * This could be made in a more "fancy" way, but it's not necessary for the current requirements.
     */
    private function isClassDefinition(string $line): bool
    {
        return str_starts_with($line, 'class ')
            || str_starts_with($line, 'readonly class ')
            || str_starts_with($line, 'final class ')
            || str_starts_with($line, 'final readonly class ');
    }
}
