<?php

declare(strict_types=1);

namespace Stability;

use Stability\Component\Component;
use Stability\Component\ComponentParser;
use Stability\Component\ComponentResult;
use Stability\Component\ComponentUsageMap;
use Stability\Config\ConfigLoader;
use Stability\Config\Module\Module;
use Stability\Exception\StabilityException;
use Stability\Infrastructure\PHP\PhpComponentParser;
use Stability\Infrastructure\PHP\PhpFileReader;
use Stability\Infrastructure\PHP\PhpStandardFileParser;
use Stability\Metric\Calculator;

readonly class Stability
{
    public function __construct(
        private ComponentParser $componentParser,
        private ConfigLoader $configLoader,
        private OutputWriter $outputWriter,
    ) {
    }

    /**
     * @throws StabilityException
     */
    public function calculate(string $configPath): void
    {
        $config = $this->configLoader->load($configPath);

        /** @var array<Component> $components */
        $components = array_map(
            fn(Module $module) => $this->componentParser->parse($config, $module),
            $config->modules,
        );

        $map = ComponentUsageMap::from($components);

        /** @var array<ComponentResult> $componentResults */
        $componentResults = array_map(
            fn(Component $component) => $this->calculateComponentMetrics(
                $component,
                $map,
            ),
            $components,
        );

        $result = new StabilityResult($componentResults);

        $this->outputWriter->outputResult($result);
    }

    private function calculateComponentMetrics(
        Component $component,
        ComponentUsageMap $map,
    ): ComponentResult {
        $abstractness = Calculator::abstractness(
            $component->abstractClasses,
            $component->interfaces,
            $component->totalClasses,
        );

        $instability = Calculator::instability(
            $map->fanInDependencies($component),
            $map->fanOutDependencies($component),
        );

        $dms = Calculator::dms($instability, $abstractness);

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $component->module->thresholdZoneOfPain,
            $component->module->thresholdZoneOfUselessness,
        );

        return new ComponentResult(
            $component,
            $abstractness,
            $instability,
            $dms,
            $zone,
        );
    }

    /**
     * For now this only supports PHP components, but it can always be extended in the future.
     */
    public static function create(ConfigLoader $configLoader, OutputWriter $outputWriter): self
    {
        $componentParser = new PhpComponentParser(
            new PhpFileReader(),
            new PhpStandardFileParser(),
        );

        return new self(
            $componentParser,
            $configLoader,
            $outputWriter,
        );
    }
}
