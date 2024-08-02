<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\Class\ClassData;
use Stability\Component\Class\ClassType;
use Stability\Component\Component;
use Stability\Tests\_Fixtures\Config\ModuleFactory;

class ComponentFactory
{
    public static function module1(): Component
    {
        $classData = [
            ClassDataFactory::abstract1(),
            ClassDataFactory::class1(),
            ClassDataFactory::interface1(),
        ];

        return new Component(
            ModuleFactory::module1(),
            'tests\_Fixtures\_TestSrc\Module1',
            1,
            1,
            3,
            $classData,
        );
    }

    public static function module2(): Component
    {
        $classData = [
            ClassDataFactory::abstract2(),
            ClassDataFactory::class2(),
        ];

        return new Component(
            ModuleFactory::module2(),
            'tests\_Fixtures\_TestSrc\Module2',
            1,
            0,
            2,
            $classData,
        );
    }

    public static function module3(): Component
    {
        $classData = [ClassDataFactory::class3()];

        return new Component(
            ModuleFactory::module3(),
            'tests\_Fixtures\_TestSrc\Module3',
            0,
            0,
            1,
            $classData,
        );
    }
}
