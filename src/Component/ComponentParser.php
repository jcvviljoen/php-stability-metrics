<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Exception\InvalidFileException;
use Stability\Config\Module;

interface ComponentParser
{
    /**
     * @param list<Module> $modules
     *
     * @throws InvalidComponentException | InvalidFileException
     */
    public function parse(array $modules): ComponentCollection;
}
