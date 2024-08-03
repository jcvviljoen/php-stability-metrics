<?php

declare(strict_types=1);

namespace Stability\Infrastructure\PHP;

use Exception;
use Override;
use Stability\Component\Class\ClassData;
use Stability\Component\Class\ClassType;
use Stability\FileParser;

/**
 * This parser only allows standard classes to be parsed.
 * It does not support traits or other PHP constructs given their intended usage.
 *
 * A separate parser could be added in the future should their analysis requirements become clear.
 */
readonly class PhpStandardFileParser implements FileParser
{
    private const string NAMESPACE_SEPARATOR = '\\';

    #[Override] public function determineSharedNamespace(string $modulePath): string
    {
        return str_replace(
            DIRECTORY_SEPARATOR,
            self::NAMESPACE_SEPARATOR,
            $modulePath,
        );
    }

    #[Override] public function parse(string $filePath, string $sharedNamespace): ClassData
    {
        $type = ClassType::UNKNOWN;
        $imports = [];

        // Open the file for reading
        $file = @fopen($filePath, 'r')
            ?: throw new Exception("Could not open file \"$filePath\" for reading.");

        while (($line = fgets($file)) !== false) {
            if (str_starts_with($line, 'use ')) {
                $imports[] = str_replace('use ', '', rtrim($line, ";\n"));

                continue;
            }

            if (str_contains($line, 'abstract class')) {
                $type = ClassType::ABSTRACT_CLASS;

                break;
            }

            if (str_starts_with($line, 'interface')) {
                $type = ClassType::INTERFACE;

                break;
            }

            if (str_starts_with($line, 'enum')) {
                $type = ClassType::ENUM;

                break;
            }

            if (str_contains($line, 'class')) {
                $type = ClassType::CONCRETE_CLASS;

                break;
            }

            // We don't need to read the file past the class definition.
            if (str_starts_with($line, '{') || str_ends_with($line, '{')) {
                break;
            }
        }

        fclose($file);

        if ($type === ClassType::UNKNOWN) {
            return new ClassData($type, []);
        }

        $imports = array_values(array_filter(
            $imports,
            fn(string $import) => str_contains($import, self::NAMESPACE_SEPARATOR)
                && !str_contains(strtolower($import), strtolower($sharedNamespace)),
        ));

        return new ClassData($type, $imports);
    }
}
