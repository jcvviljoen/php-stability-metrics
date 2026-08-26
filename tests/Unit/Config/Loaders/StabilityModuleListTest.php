<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Loaders;

use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\StabilityModuleList;
use Stability\Tests\_Fixtures\Config\StabilityModuleFactory;
use Stability\Tests\ExpectThrows;

class StabilityModuleListTest extends TestCase
{
    use ExpectThrows;

    public function test_given_an_empty_modules_array_then_list_throws_exception(): void
    {
        $modules = [];

        $exception = $this->expectThrows(fn() => new StabilityModuleList($modules));

        $this->assertEquals(
            InvalidConfigurationException::onMissingModules(),
            $exception,
        );
    }

    public function test_given_two_modules_of_the_same_name_then_list_throws_exception(): void
    {
        $module = StabilityModuleFactory::module1();

        $exception = $this->expectThrows(fn() => new StabilityModuleList([$module, $module]));

        $this->assertEquals(
            InvalidConfigurationException::onDuplicateModuleName('Module1'),
            $exception,
        );
    }

    public function test_given_a_module_list_when_getting_values_returns_plain_array(): void
    {
        $module1 = StabilityModuleFactory::module1();
        $module2 = StabilityModuleFactory::module2();
        $modules = new StabilityModuleList([$module1, $module2]);

        $values = $modules->values();

        $this->assertSame([$module1, $module2], $values);
    }
}
