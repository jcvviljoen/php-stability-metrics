<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\Type;

class TypeTest extends TestCase
{
    public function test_given_a_type_then_check_if_it_is_an_abstract_class(): void
    {
        $abstract = Type::ABSTRACT_CLASS;
        $concrete = Type::CONCRETE_CLASS;

        $this->assertTrue($abstract->isAbstractClass());
        $this->assertFalse($concrete->isAbstractClass());
    }

    public function test_given_a_type_then_check_if_it_is_a_concrete_class(): void
    {
        $concrete = Type::CONCRETE_CLASS;
        $abstract = Type::ABSTRACT_CLASS;

        $this->assertTrue($concrete->isConcreteClass());
        $this->assertFalse($abstract->isConcreteClass());
    }

    public function test_given_a_type_then_check_if_it_is_an_enum(): void
    {
        $enum = Type::ENUM;
        $interface = Type::INTERFACE;

        $this->assertTrue($enum->isEnum());
        $this->assertFalse($interface->isEnum());
    }

    public function test_given_a_type_then_check_if_it_is_an_interface(): void
    {
        $interface = Type::INTERFACE;
        $unknown = Type::UNKNOWN;

        $this->assertTrue($interface->isInterface());
        $this->assertFalse($unknown->isInterface());
    }

    public function test_given_a_type_then_check_if_it_is_unknown(): void
    {
        $unknown = Type::UNKNOWN;
        $enum = Type::ENUM;

        $this->assertTrue($unknown->isUnknown());
        $this->assertFalse($enum->isUnknown());
    }
}
