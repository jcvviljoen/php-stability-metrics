<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Component;

use PHPUnit\Framework\TestCase;
use Stability\Tests\_Fixtures\Component\ComponentFactory;

class ComponentTest extends TestCase
{
    public function test_count_usages_of(): void
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
