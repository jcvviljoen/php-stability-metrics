<?php

declare(strict_types=1);

namespace Stability\Config;

use Stability\Config\Exception\InvalidOutputOptionException;

enum OutputOption: string
{
    case CONSOLE = 'console';
    case JSON = 'json';

    /**
     * Resolves the value given on the command line or in the configuration file.
     *
     * @throws InvalidOutputOptionException
     */
    public static function fromArgument(string $option): self
    {
        return self::tryFrom($option)
            ?? throw InvalidOutputOptionException::onUnsupportedOption($option);
    }
}
