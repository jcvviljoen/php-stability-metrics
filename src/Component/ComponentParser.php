<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Exception\InvalidFileException;
use Stability\Config\ModuleList;

interface ComponentParser
{
    /**
     * @throws InvalidComponentException | InvalidFileException
     */
    public function parse(ModuleList $modules): ComponentCollection;
}
