<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Component\Component;
use Stability\Component\ComponentUsageMap;
use Stability\Tests\_Fixtures\Component\ComponentFactory;

class ComponentUsageMapTest extends TestCase
{
    private ComponentUsageMap $map;

    protected function setUp(): void
    {
        parent::setUp();

        $this->map = ComponentUsageMap::from([
            ComponentFactory::module1(),
            ComponentFactory::module2(),
            ComponentFactory::module3(),
        ]);
    }

    public function test_from(): void
    {
        $this->assertEquals(
            [
                'Module1' => [
                    'Module1' => 0,
                    'Module2' => 0,
                    'Module3' => 1,
                ],
                'Module2' => [
                    'Module1' => 1,
                    'Module2' => 0,
                    'Module3' => 0,
                ],
                'Module3' => [
                    'Module1' => 0,
                    'Module2' => 0,
                    'Module3' => 0,
                ],
            ],
            $this->map->mappings,
        );
    }

    #[DataProvider('provideFanInComponents')]
    public function test_fan_in_dependencies(
        Component $component,
        int $expected,
    ): void {
        $fanIn = $this->map->fanInDependencies($component);

        $this->assertEquals($expected, $fanIn);
    }

    #[DataProvider('provideFanOutComponents')]
    public function test_fan_out_dependencies(
        Component $component,
        int $expected,
    ): void {
        $fanOut = $this->map->fanOutDependencies($component);

        $this->assertEquals($expected, $fanOut);
    }

    /**
     * @return array<string, array{component: Component, expected: int}>
     */
    public static function provideFanInComponents(): array
    {
        return [
            'When a component is imported into a class in another component, then' => [
                'component' => ComponentFactory::module1(),
                'expected' => 1,
            ],
            'When a class in a component imports another component, but itself is unused, then' => [
                'component' => ComponentFactory::module2(),
                'expected' => 0,
            ],
            'When a component has no imports, but is imported into a class in another module, then' => [
                'component' => ComponentFactory::module3(),
                'expected' => 1,
            ],
        ];
    }

    /**
     * @return array<string, array{component: Component, expected: int}>
     */
    public static function provideFanOutComponents(): array
    {
        return [
            'When a component imports a class from another component, then' => [
                'component' => ComponentFactory::module1(),
                'expected' => 1,
            ],
            'When a component imports a class from another component, but itself is unused, then' => [
                'component' => ComponentFactory::module2(),
                'expected' => 1,
            ],
            'When a component has no imports, but is imported into a class in another module, then' => [
                'component' => ComponentFactory::module3(),
                'expected' => 0,
            ],
        ];
    }
}
