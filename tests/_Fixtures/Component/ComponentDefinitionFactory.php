<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\ComponentDefinition;

readonly class ComponentDefinitionFactory
{
    public static function module1(): ComponentDefinition
    {
        return new ComponentDefinition(
            'Module1',
            'Module1',
            ThresholdsFactory::default(),
        );
    }
}
