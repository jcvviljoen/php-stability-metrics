<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use Stability\Component\Component;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\MetadataCollection;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Component\ThresholdsFactory;
use Stability\Tests\ExpectThrows;

class ComponentTest extends TestCase
{
    use ExpectThrows;

    public function test_given_a_component_when_reading_the_name_then_return_its_name(): void
    {
        $component = ComponentFactory::module1();

        $this->assertEquals('Module1', $component->name());
    }

    public function test_given_an_empty_primary_namespace_then_reading_it_throws_exception(): void
    {
        $component = new Component(
            'Unknown',
            '',
            MetadataCollection::empty(),
            ThresholdsFactory::default(),
        );

        $exception = $this->expectThrows(fn() => $component->primaryNamespace());

        $this->assertEquals(
            InvalidComponentException::onEmptyComponent('Unknown'),
            $exception,
        );
    }

    public function test_given_a_filled_namespace_then_read_the_namespace(): void
    {
        $component = ComponentFactory::module1();

        $this->assertEquals('Stability\Tests\_Fixtures\_TestSrc\Module1', $component->primaryNamespace());
    }

    public function test_given_a_component_then_count_abstract_classes(): void
    {
        $component = ComponentFactory::module1();

        $this->assertEquals(1, $component->countAbstractClasses());
    }

    public function test_given_a_component_then_count_interfaces(): void
    {
        $component = ComponentFactory::module1();

        $this->assertEquals(1, $component->countInterfaces());
    }

    public function test_given_a_component_then_count_total_classes(): void
    {
        $component = ComponentFactory::module1();

        $this->assertEquals(3, $component->countTotalClasses());
    }

    public function test_count_usages_of_another_component(): void
    {
        $component1 = ComponentFactory::module1();
        $component2 = ComponentFactory::module2();
        $component3 = ComponentFactory::module3();

        $this->assertEquals(0, $component1->countUsagesOf($component1));

        $this->assertEquals(0, $component1->countUsagesOf($component2));
        $this->assertEquals(1, $component2->countUsagesOf($component1));

        $this->assertEquals(1, $component1->countUsagesOf($component3));
        $this->assertEquals(0, $component3->countUsagesOf($component1));
    }
}
