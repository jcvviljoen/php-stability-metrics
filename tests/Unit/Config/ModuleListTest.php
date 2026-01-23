<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\ModuleList;
use Stability\Tests\_Fixtures\Config\ModuleFactory;
use Stability\Tests\ExpectThrows;

class ModuleListTest extends TestCase
{
    use ExpectThrows;

    public function test_given_an_empty_modules_array_then_list_throws_exception(): void
    {
        $modules = [];

        $exception = $this->expectThrows(fn() => new ModuleList($modules));

        $this->assertEquals(
            InvalidConfigurationException::onMissingModules(),
            $exception,
        );
    }

    public function test_given_a_module_list_when_module_name_exists_then_adding_module_throws_exception(): void
    {
        $module = ModuleFactory::module1();
        $modules = new ModuleList([$module]);

        $exception = $this->expectThrows(fn() => $modules->add($module));

        $this->assertEquals(
            InvalidConfigurationException::onDuplicateModuleName('Module1'),
            $exception,
        );
    }

    public function test_given_a_module_list_when_getting_values_returns_plain_array(): void
    {
        $module1 = ModuleFactory::module1();
        $module2 = ModuleFactory::module2();
        $modules = new ModuleList([$module1, $module2]);

        $values = $modules->values();

        $this->assertSame([$module1, $module2], $values);
    }
}
