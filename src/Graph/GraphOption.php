<?php

declare(strict_types=1);

namespace Stability\Graph;

use Stability\Graph\Exception\InvalidGraphOptionException;

enum GraphOption: string
{
    case MERMAID = 'mermaid';
    case DOT = 'dot';

    /**
     * Resolves the value given on the command line.
     *
     * @throws InvalidGraphOptionException
     */
    public static function fromArgument(string $option): self
    {
        return self::tryFrom($option)
            ?? throw InvalidGraphOptionException::onUnsupportedRenderer($option);
    }
}
