<?php

declare(strict_types=1);

namespace Stability\Component\Parsers\PHP;

use Stability\Component\Exception\InvalidComponentException;

readonly class PhpNamespaceParser
{
    /**
     * @param list<string> $namespaces
     *
     * @throws InvalidComponentException
     */
    public function primaryNamespace(array $namespaces): string
    {
        if (empty($namespaces)) {
            return '';
        }

        $splitNamespaces = array_map(fn($namespace) => explode('\\', $namespace), $namespaces);
        $primaryParts = $splitNamespaces[0];

        foreach ($splitNamespaces as $parts) {
            $length = min(count($primaryParts), count($parts));

            for ($i = 0; $i < $length; $i++) {
                if ($primaryParts[$i] !== $parts[$i]) {
                    $primaryParts = array_slice($primaryParts, 0, $i);

                    break 2;
                }
            }

            $primaryParts = array_slice($primaryParts, 0, $length);
        }

        $primary = implode('\\', $primaryParts);

        foreach ($namespaces as $namespace) {
            if (!str_starts_with($namespace, $primary . '\\') && $namespace !== $primary) {
                throw InvalidComponentException::onMismatchedNamespace($namespace, $primary);
            }
        }

        return $primary;
    }
}
