<?php

namespace Instability\Infrastructure;

use Exception;
use Instability\Component\FileData;
use Instability\Component\FileType;
use Instability\FileParser;
use Override;

readonly class PhpFileParser implements FileParser
{
    #[Override] public function parse(string $filePath, string $namespaceMatcher): ?FileData
    {
        $type = FileType::UNKNOWN;
        $imports = [];

        // Open the file for reading
        $file = fopen($filePath, 'r')
            ?: throw new Exception("Could not open file '$filePath'");

        while (($line = fgets($file)) !== false) {
            if (str_starts_with($line, 'use ')) {
                $imports[] = str_replace('use ', '', rtrim($line, ";\n"));
                continue;
            }

            if (str_contains($line, 'abstract class')) {
                $type = FileType::ABSTRACT_CLASS;
                break;
            }

            if (str_starts_with($line, 'interface')) {
                $type = FileType::INTERFACE;
                break;
            }

            if (str_contains($line, 'class')) {
                $type = FileType::T_CLASS;
                break;
            }

            // We don't need to read the file past the class definition.
            if (str_starts_with($line, '{') || str_ends_with($line, '{')) {
                break;
            }
        }

        fclose($file);

        if ($type === FileType::UNKNOWN) {
            return null; // TODO should we rather throw an exception here for the user to fix?
        }

        /**
         * Only allow standard classes to be checked & filter out internal imports.
         */
        $imports = array_filter(
            $imports,
            fn(string $import) => str_contains($import, "\\")
                && !str_contains(strtolower($import), strtolower($namespaceMatcher))
        );

        return new FileData($imports, $type);
    }
}
