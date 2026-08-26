<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use Stability\Component\Component;
use Stability\Component\Exception\InvalidComponentException;
use Stability\Component\File\Metadata;
use Stability\Component\File\MetadataCollection;
use Stability\Component\File\Type;
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

    public function test_given_an_empty_primary_namespace_then_the_component_cannot_be_built(): void
    {
        $exception = $this->expectThrows(fn() => new Component(
            'Unknown',
            '',
            MetadataCollection::empty(),
            ThresholdsFactory::default(),
        ));

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

    public function test_given_a_component_whose_namespace_prefixes_another_then_do_not_count_it(): void
    {
        $foo = $this->componentNamed('Foo', 'App\Foo', ['App\FooBar\Thing', 'App\Foo\Thing']);
        $fooBar = $this->componentNamed('FooBar', 'App\FooBar', []);

        // Only "App\Foo\Thing" belongs to Foo, so FooBar is used once and not twice.
        $this->assertEquals(1, $foo->countUsagesOf($fooBar));
    }

    /**
     * A component whose files sit in several sub-namespaces is named by the parent they
     * share, which is what the namespace parser narrows to. Every count downstream keys
     * off that parent, so a dependency on any one of the sub-namespaces has to land on
     * the component holding them, and an import from any one of them has to count as
     * that component's own.
     */
    public function test_given_a_component_spanning_sub_namespaces_then_count_usages_across_all_of_them(): void
    {
        $dataSource = new Component(
            'DataSource',
            'Domain\DataSource',
            new MetadataCollection([
                new Metadata(Type::CONCRETE_CLASS, 'Domain\DataSource\Models', ['Domain\User\Models\User']),
                new Metadata(Type::CONCRETE_CLASS, 'Domain\DataSource\Repositories', ['Domain\User\Enums\Status']),
                new Metadata(Type::CONCRETE_CLASS, 'Domain\DataSource\Tests\Mocks', []),
            ]),
            ThresholdsFactory::default(),
        );

        $user = $this->componentNamed('User', 'Domain\User', [
            'Domain\DataSource\Models\DataSource',
            'Domain\DataSource\Tests\Mocks\FakeDataSource',
        ]);

        $this->assertEquals(2, $dataSource->countUsagesOf($user));
        $this->assertEquals(2, $user->countUsagesOf($dataSource));
    }

    public function test_given_an_aliased_or_function_import_then_still_count_it(): void
    {
        $user = $this->componentNamed('User', 'App\User', [
            'App\Other\Thing as Aliased',
            'function App\Other\helper',
            'const App\Other\SOME_CONSTANT',
        ]);
        $other = $this->componentNamed('Other', 'App\Other', []);

        $this->assertEquals(3, $user->countUsagesOf($other));
    }

    public function test_given_an_import_of_the_namespace_itself_then_count_it(): void
    {
        $user = $this->componentNamed('User', 'App\User', ['App\Other']);
        $other = $this->componentNamed('Other', 'App\Other', []);

        $this->assertEquals(1, $user->countUsagesOf($other));
    }

    /**
     * @param list<string> $imports
     */
    private function componentNamed(string $name, string $namespace, array $imports): Component
    {
        return new Component(
            $name,
            $namespace,
            new MetadataCollection([new Metadata(Type::CONCRETE_CLASS, $namespace, $imports)]),
            ThresholdsFactory::default(),
        );
    }
}
