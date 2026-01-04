<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use Stability\Component\ComponentCollection;
use Stability\Component\DependencyMap;
use Stability\Tests\_Fixtures\Component\ComponentFactory;

class ComponentCollectionTest extends TestCase
{
    public function test_create_an_empty_instance(): void
    {
        $collection = ComponentCollection::empty();

        $this->assertEmpty($collection->values());
    }

    public function test_add_component_to_collection(): void
    {
        $collection = ComponentCollection::empty();

        $collection->add(ComponentFactory::module1());

        $this->assertEquals(
            [ComponentFactory::module1()],
            $collection->values(),
        );
    }

    public function test_given_a_collection_then_the_inner_dependencies_can_be_mapped(): void
    {
        $collection = new ComponentCollection([
            ComponentFactory::module1(),
            ComponentFactory::module2(),
        ]);

        $dependencyMap = $collection->dependencyMap();

        $this->assertEquals(
            new DependencyMap([
                'Module1' => ['Module1' => 0, 'Module2' => 0],
                'Module2' => ['Module1' => 1, 'Module2' => 0],
            ]),
            $dependencyMap,
        );
    }
}
