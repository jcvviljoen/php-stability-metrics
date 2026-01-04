<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component\File;

use PHPUnit\Framework\TestCase;
use Stability\Component\File\MetadataCollection;
use Stability\Tests\_Fixtures\Component\MetadataFactory;

class MetadataCollectionTest extends TestCase
{
    public function test_when_creating_an_empty_instance_then_it_has_no_items(): void
    {
        $collection = MetadataCollection::empty();

        $this->assertCount(0, $collection);
    }

    public function test_given_a_collection_then_a_new_item_can_be_added(): void
    {
        $collection = MetadataCollection::empty();
        $metadata = MetadataFactory::abstract1();

        $collection->add($metadata);

        $this->assertEquals(
            new MetadataCollection([$metadata]),
            $collection,
        );
    }

    public function test_given_a_collection_then_get_unique_namespaces_from_all_metadata(): void
    {
        $collection = new MetadataCollection([
            // Same namespace
            MetadataFactory::abstract1(),
            MetadataFactory::class1(),
            // Different namespace
            MetadataFactory::class2(),
        ]);

        $namespaces = $collection->namespaces();

        $this->assertEquals(
            [
                'Stability\Tests\_Fixtures\_TestSrc\Module1',
                'Stability\Tests\_Fixtures\_TestSrc\Module2',
            ],
            $namespaces,
        );
    }

    public function test_given_a_collection_then_count_class_types(): void
    {
        $collection = new MetadataCollection([
            MetadataFactory::abstract1(),
            MetadataFactory::class1(),
            MetadataFactory::interface1(),
            MetadataFactory::abstract2(),
            MetadataFactory::class2(),
            MetadataFactory::class3(),
            MetadataFactory::unknown(),
        ]);

        $this->assertEquals(2, $collection->countAbstractClasses());
        $this->assertEquals(1, $collection->countInterfaces());
        $this->assertEquals(6, $collection->countTotalClasses());
    }

    public function test_given_a_collection_then_get_all_valid_values(): void
    {
        $collection = new MetadataCollection([
            MetadataFactory::abstract1(),
            MetadataFactory::class1(),
            MetadataFactory::interface1(),
            MetadataFactory::abstract2(),
            MetadataFactory::class2(),
            MetadataFactory::class3(),
            // The unknown type should be ignored
            MetadataFactory::unknown(),
        ]);

        $this->assertEquals(
            [
                MetadataFactory::abstract1(),
                MetadataFactory::class1(),
                MetadataFactory::interface1(),
                MetadataFactory::abstract2(),
                MetadataFactory::class2(),
                MetadataFactory::class3(),
            ],
            $collection->validValues(),
        );
    }
}
