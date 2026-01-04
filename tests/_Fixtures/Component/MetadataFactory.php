<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\File\Metadata;
use Stability\Component\File\MetadataCollection;
use Stability\Component\File\Type;

class MetadataFactory
{
    public static function abstract1(): Metadata
    {
        return new Metadata(
            Type::ABSTRACT_CLASS,
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
            [],
        );
    }

    public static function class1(): Metadata
    {
        return new Metadata(
            Type::CONCRETE_CLASS,
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
            ['Stability\Tests\_Fixtures\_TestSrc\Module3\Class3'],
        );
    }

    public static function interface1(): Metadata
    {
        return new Metadata(
            Type::INTERFACE,
            'Stability\Tests\_Fixtures\_TestSrc\Module1',
            [],
        );
    }

    public static function abstract2(): Metadata
    {
        return new Metadata(
            Type::ABSTRACT_CLASS,
            'Stability\Tests\_Fixtures\_TestSrc\Module2',
            [],
        );
    }

    public static function class2(): Metadata
    {
        return new Metadata(
            Type::CONCRETE_CLASS,
            'Stability\Tests\_Fixtures\_TestSrc\Module2',
            ['Stability\Tests\_Fixtures\_TestSrc\Module1\Class1'],
        );
    }

    public static function class3(): Metadata
    {
        return new Metadata(
            Type::CONCRETE_CLASS,
            'Stability\Tests\_Fixtures\_TestSrc\Module3',
            [],
        );
    }

    public static function unknown(): Metadata
    {
        return new Metadata(
            Type::UNKNOWN,
            '',
            [],
        );
    }

    public static function testSourceCollection(): MetadataCollection
    {
        return new MetadataCollection([
            self::abstract1(),
            self::class1(),
            self::interface1(),
            self::abstract2(),
            self::class2(),
            self::class3(),
        ]);
    }
}
