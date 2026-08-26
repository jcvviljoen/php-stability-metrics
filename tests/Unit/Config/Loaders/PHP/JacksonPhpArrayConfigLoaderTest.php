<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Loaders\PHP;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\PHP\JacksonPhpArrayConfigLoader;
use Stability\Tests\_Fixtures\Config\StabilityConfigFactory;
use Stability\Tests\ExpectThrows;

class JacksonPhpArrayConfigLoaderTest extends TestCase
{
    use ExpectThrows;

    private JacksonPhpArrayConfigLoader $loader;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->loader = new JacksonPhpArrayConfigLoader();
    }

    public function test_given_a_config_when_valid_then_load_config(): void
    {
        $config = __DIR__ . '/_Fixtures/config_valid.php';

        $result = $this->loader->load($config);

        $this->assertEquals(StabilityConfigFactory::baseValid(), $result);
    }

    public function test_given_a_config_when_file_does_not_exist_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/does_not_exist.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingConfigFile($config), $exception);
    }

    public function test_given_a_config_when_a_value_cannot_be_mapped_then_throw_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_invalid_output_option.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        $this->assertStringContainsString("Configuration file \"$config\" could not be read", $exception->getMessage());
        $this->assertStringContainsString('.outputSettings.option', $exception->getMessage());
        $this->assertStringContainsString('"xml" is not a valid backing value', $exception->getMessage());
    }

    public function test_given_a_config_when_it_fails_validation_then_throw_the_original_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_duplicate_module_name.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(
            InvalidConfigurationException::onDuplicateModuleName('Module1')->getMessage(),
            $exception->getMessage(),
        );
    }
}
