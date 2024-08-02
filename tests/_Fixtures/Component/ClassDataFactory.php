<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\Class\ClassData;
use Stability\Component\Class\ClassType;

class ClassDataFactory
{
    public static function abstract1(): ClassData
    {
        return new ClassData(ClassType::ABSTRACT_CLASS, []);
    }

    public static function class1(): ClassData
    {
        return new ClassData(ClassType::CONCRETE_CLASS, ['Stability\Tests\_Fixtures\_TestSrc\Module3\Class3']);
    }

    public static function interface1(): ClassData
    {
        return new ClassData(ClassType::INTERFACE, []);
    }

    public static function abstract2(): ClassData
    {
        return new ClassData(ClassType::ABSTRACT_CLASS, []);
    }

    public static function class2(): ClassData
    {
        return new ClassData(ClassType::CONCRETE_CLASS, ['Stability\Tests\_Fixtures\_TestSrc\Module1\Class1']);
    }

    public static function class3(): ClassData
    {
        return new ClassData(ClassType::CONCRETE_CLASS, []);
    }

    public static function unknown(): ClassData
    {
        return new ClassData(ClassType::UNKNOWN, []);
    }
}
