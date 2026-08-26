<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Exception\InvalidFileException;

interface ComponentParser
{
    /**
     * @param list<ComponentDefinition> $definitions
     *
     * @throws InvalidComponentException | InvalidFileException
     */
    public function parse(array $definitions): ComponentCollection;
}
