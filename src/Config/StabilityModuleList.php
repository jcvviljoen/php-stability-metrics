<?php

declare(strict_types=1);

namespace Stability\Config;

use Override;
use Stability\Config\Exception\InvalidConfigurationException;
use Traversable;

readonly class StabilityModuleList implements ModuleList
{
    /**
     * @var array<string, Module> $modules
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
            throw InvalidConfigurationException::onMissingModules();
        }

        $this->modules = $this->keyedByName($modules);
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

    /**
     * Names have to be unique, because a name is how a component is identified in every
     * report and in the dependency map.
     *
     * @param list<StabilityModule> $modules
     *
     * @return array<string, Module>
     *
     * @throws InvalidConfigurationException
     */
    private function keyedByName(array $modules): array
    {
        $keyed = [];

        foreach ($modules as $module) {
            if (isset($keyed[$module->name()])) {
                throw InvalidConfigurationException::onDuplicateModuleName($module->name());
            }

            $keyed[$module->name()] = $module;
        }

        return $keyed;
    }
}
