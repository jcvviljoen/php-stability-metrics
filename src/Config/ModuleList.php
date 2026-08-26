<?php

declare(strict_types=1);

namespace Stability\Config;

use IteratorAggregate;

/**
 * @extends IteratorAggregate<string, Module>
 */
interface ModuleList extends IteratorAggregate
{
    /**
     * @return list<Module>
     */
    public function values(): array;
}
