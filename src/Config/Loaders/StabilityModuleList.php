<?php

declare(strict_types=1);

namespace Stability\Config\Loaders;

use Override;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Module;
use Stability\Config\ModuleList;
use Traversable;

class StabilityModuleList implements ModuleList
{
    /**
     * @var array<string, StabilityModule> $modules
     */
    private array $modules;

    /**
     * @param list<StabilityModule> $modules
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
     * @inheritDoc
     */
    #[Override]
    public function add(Module $module): void
    {
        assert($module instanceof StabilityModule);

        if (isset($this->modules[$module->name()])) {
            throw InvalidConfigurationException::onDuplicateModuleName($module->name());
        }

        $this->modules[$module->name()] = $module;
    }

    /**
     * @inheritDoc
     */
    #[Override]
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
