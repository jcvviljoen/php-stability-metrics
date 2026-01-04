<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Loaders\PHP;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\PHP\PhpArrayConfigLoader;
use Stability\Output\OutputOption;
use Stability\Output\OutputSetting;
use Stability\Tests\_Fixtures\Config\ConfigFactory;
use Stability\Tests\ExpectThrows;

class PhpArrayConfigLoaderTest extends TestCase
{
    use ExpectThrows;

    private PhpArrayConfigLoader $loader;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->loader = new PhpArrayConfigLoader();
    }

    public function test_given_a_config_when_valid_then_load_config(): void
    {
        $config = __DIR__ . '/_Fixtures/config_valid.php';

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
        $config = __DIR__ . '/_Fixtures/config_missing_base_path.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingBasePath(), $exception);
    }

    public function test_given_a_config_when_modules_is_missing_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_missing_modules.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModules(), $exception);
    }

    public function test_given_a_config_when_module_is_missing_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_missing_module.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModule(), $exception);
    }

    public function test_given_a_config_when_output_is_set_without_output_option_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_output_without_option.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingOutputOption(), $exception);
    }

    public function test_given_a_config_when_output_is_set_with_custom_options_then_load_config(): void
    {
        $config = __DIR__ . '/_Fixtures/config_output_with_option.php';
        $expectedSetting = new OutputSetting(OutputOption::JSON, 'some-output', '/var/logs');
        $expectedConfig = ConfigFactory::baseValid();
        $expectedConfig->overrideOutputSettings($expectedSetting);

        $result = $this->loader->load($config);

        $this->assertEquals($expectedConfig, $result);
    }
}
