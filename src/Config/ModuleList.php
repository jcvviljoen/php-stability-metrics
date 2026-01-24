<?php

declare(strict_types=1);

namespace Stability\Config;

use IteratorAggregate;
use Stability\Config\Exception\InvalidConfigurationException;

/**
 * @extends IteratorAggregate<string, Module>
 */
interface ModuleList extends IteratorAggregate
{
    /**
     * @throws InvalidConfigurationException
     */
    public function add(Module $module): void;

    /**
     * @return list<Module>
     */
    public function values(): array;
}
