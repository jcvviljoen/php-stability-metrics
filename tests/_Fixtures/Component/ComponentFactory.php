<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\Component;
use Stability\Component\File\MetadataCollection;
use Stability\Tests\_Fixtures\Config\StabilityModuleFactory;

readonly class ComponentFactory
{
    public static function module1(): Component
    {
        $metadata = new MetadataCollection([
            MetadataFactory::abstract1(),
            MetadataFactory::class1(),
            MetadataFactory::interface1(),
        ]);

        return new Component(
            StabilityModuleFactory::module1(),
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
            $metadata,
        );
    }

    public static function module2(): Component
    {
        $metadata = new MetadataCollection([
            MetadataFactory::abstract2(),
            MetadataFactory::class2(),
        ]);

        return new Component(
            StabilityModuleFactory::module2(),
            'Stability\Tests\_Fixtures\_TestSrc\Module2',
            $metadata,
        );
    }

    public static function module3(): Component
    {
        $metadata = new MetadataCollection([MetadataFactory::class3()]);

        return new Component(
            StabilityModuleFactory::module3(),
            'Stability\Tests\_Fixtures\_TestSrc\Module3',
            $metadata,
        );
    }
}
