<?php

declare(strict_types=1);

namespace Stability\Component;

use Stability\Config\Config;
use Stability\Config\Module\Module;
use Stability\Exception\InvalidFileException;
use Stability\Exception\InvalidModuleException;

interface ComponentParser
{
    /**
     * @throws InvalidModuleException | InvalidFileException
     */
    public function parse(Config $config, Module $module): Component;
}
