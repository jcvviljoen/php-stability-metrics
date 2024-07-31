<?php

namespace Instability\Tests\_Fixtures;

use Instability\Component\ClassCounter;
use Instability\Component\Component;
use Instability\Component\FileData;
use Instability\Component\FileType;

class ComponentFactory
{
    public static function module1(): Component
    {
        $fileData = [
            new FileData([], FileType::ABSTRACT_CLASS),
            new FileData(['Instability\Tests\Unit\_Fixtures\Stability\Module3\Class3'], FileType::CONCRETE_CLASS),
            new FileData([], FileType::INTERFACE),
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
            new FileData([], FileType::ABSTRACT_CLASS),
            new FileData(['Instability\Tests\Unit\_Fixtures\Stability\Module1\Class1'], FileType::CONCRETE_CLASS),
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
            new FileData([], FileType::CONCRETE_CLASS),
        ];

        return new Component(
            'Module3',
            '_Fixtures\Stability\Module3',
            $fileData,
            new ClassCounter(0, 0, 1),
        );
    }
}
