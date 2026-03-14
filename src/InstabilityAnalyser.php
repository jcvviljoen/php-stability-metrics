<?php

declare(strict_types=1);

namespace Stability;

use Stability\Component\Component;
use Stability\Component\ComponentCollection;
use Stability\Component\DependencyMap;
use Stability\Metric\Calculator;
use Stability\Metric\Result;
use Stability\Metric\StableDependencyMetric;

readonly class InstabilityAnalyser
{
    public function analyse(ComponentCollection $components): Result
    {
        $dependencyMap = $components->dependencyMap();

        /** @var list<StableDependencyMetric> $componentResults */
        $componentResults = array_map(
            fn(Component $component) => $this->calculateComponentMetrics(
                $component,
                $dependencyMap,
            ),
            $components->values(),
        );

        return new Result($componentResults, $dependencyMap);
    }

    private function calculateComponentMetrics(
        Component $component,
        DependencyMap $map,
    ): StableDependencyMetric {
        $abstractness = Calculator::abstractness(
            $component->countAbstractClasses(),
            $component->countInterfaces(),
            $component->countTotalClasses(),
        );

        $instability = Calculator::instability(
            $map->countFanIn($component),
            $map->countFanOut($component),
        );

        $dms = Calculator::dms($instability, $abstractness);

        $zone = Calculator::zone(
            $abstractness,
            $instability,
            $dms,
            $component->module->thresholdZoneOfPain(),
            $component->module->thresholdZoneOfUselessness(),
        );

        return new StableDependencyMetric(
            $component,
            $zone,
            $abstractness,
            $instability,
            $dms,
        );
    }
}
