<?php

namespace Stability\Tests\_Fixtures;

use Stability\Component\ClassCounter;
use Stability\Component\Component;
use Stability\Component\FileData;
use Stability\Component\FileType;

class ComponentFactory
{
    public static function module1(): Component
    {
        $fileData = [
            new FileData(FileType::ABSTRACT_CLASS, []),
            new FileData(FileType::CONCRETE_CLASS, ['Stability\Tests\Unit\_Fixtures\Stability\Module3\Class3']),
            new FileData(FileType::INTERFACE, []),
        ];

        return new Component(
            'Module1',
            '_Fixtures\Stability\Module1',
            $fileData,
            new ClassCounter(1, 1, 3),
        );
    }

    public static function module2(): Component
    {
        $fileData = [
            new FileData(FileType::ABSTRACT_CLASS, []),
            new FileData(FileType::CONCRETE_CLASS, ['Stability\Tests\Unit\_Fixtures\Stability\Module1\Class1']),
        ];

        return new Component(
            'Module2',
            '_Fixtures\Stability\Module2',
            $fileData,
            new ClassCounter(1, 0, 2),
        );
    }

    public static function module3(): Component
    {
        $fileData = [
            new FileData(FileType::CONCRETE_CLASS, []),
        ];

        return new Component(
            'Module3',
            '_Fixtures\Stability\Module3',
            $fileData,
            new ClassCounter(0, 0, 1),
        );
    }
}
