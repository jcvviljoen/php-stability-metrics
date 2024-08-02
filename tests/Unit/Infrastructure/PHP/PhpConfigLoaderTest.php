<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\PHP;

use PHPUnit\Framework\TestCase;
use Stability\Exception\InvalidConfigurationException;
use Stability\Infrastructure\PHP\PhpConfigLoader;
use Stability\Tests\_Fixtures\Config\ConfigFactory;
use Stability\Tests\ExpectThrows;

class PhpConfigLoaderTest extends TestCase
{
    use ExpectThrows;

    private PhpConfigLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader = new PhpConfigLoader();
    }

    public function test_given_a_config_when_valid_then_load_config(): void
    {
        $config = __DIR__ . '/_Fixtures/valid.php';

        $result = $this->loader->load($config);

        $this->assertEquals(ConfigFactory::baseValid(), $result);
    }

    public function test_given_a_config_when_file_does_not_exist_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/does_not_exist.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingConfigFile($config), $exception);
    }

    public function test_given_a_config_when_base_path_is_missing_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/missing_base_path.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingBasePath(), $exception);
    }

    public function test_given_a_config_when_modules_is_missing_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/missing_modules.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModules(), $exception);
    }

    public function test_given_a_config_when_module_is_missing_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/missing_module.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModule(), $exception);
    }
}
