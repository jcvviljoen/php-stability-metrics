<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\Component;
use Stability\Component\File\MetadataCollection;

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
            'Module1',
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
            $metadata,
            ThresholdsFactory::default(),
        );
    }

    public static function module2(): Component
    {
        $metadata = new MetadataCollection([
            MetadataFactory::abstract2(),
            MetadataFactory::class2(),
        ]);

        return new Component(
            'Module2',
            'Stability\Tests\_Fixtures\_TestSrc\Module2',
            $metadata,
            ThresholdsFactory::default(),
        );
    }

    public static function module3(): Component
    {
        $metadata = new MetadataCollection([MetadataFactory::class3()]);

        return new Component(
            'Module3',
            'Stability\Tests\_Fixtures\_TestSrc\Module3',
            $metadata,
            ThresholdsFactory::default(),
        );
    }
}
