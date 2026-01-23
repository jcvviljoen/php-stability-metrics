<?php

declare(strict_types=1);

namespace Stability\Config;

use IteratorAggregate;
use Override;
use Stability\Config\Exception\InvalidConfigurationException;
use Traversable;

/**
 * @implements IteratorAggregate<string, Module>
 */
class ModuleList implements IteratorAggregate
{
    /**
     * @var array<string, Module> $modules
     */
    private array $modules;

    /**
     * @param list<Module> $modules
     *
     * @throws InvalidConfigurationException
     */
    public function __construct(array $modules)
    {
        if (empty($modules)) {
            throw throw InvalidConfigurationException::onMissingModules();
        }

        array_map($this->add(...), $modules);
    }

    /**
     * @throws InvalidConfigurationException
     */
    public function add(Module $module): void
    {
        if (isset($this->modules[$module->name()])) {
            throw InvalidConfigurationException::onDuplicateModuleName($module->name());
        }

        $this->modules[$module->name()] = $module;
    }

    /**
     * @return list<Module>
     */
    public function values(): array
    {
        return array_values($this->modules);
    }

    #[Override]
    public function getIterator(): Traversable
    {
        yield from $this->modules;
    }
}
