<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Config\Loaders\PHP;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stability\Config\Exception\InvalidConfigurationException;
use Stability\Config\Loaders\PHP\JacksonPhpArrayConfigLoader;
use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;
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

    public function test_given_a_config_when_output_settings_are_set_then_they_are_read(): void
    {
        $config = __DIR__ . '/_Fixtures/config_output_with_option.php';

        $result = $this->loader->load($config);

        $this->assertEquals(
            new OutputSetting(OutputOption::JSON, '/var/logs', 'some-output'),
            $result->outputSettings(),
        );
    }

    public function test_given_a_config_when_modules_are_empty_then_throw_the_original_exception(): void
    {
        $config = __DIR__ . '/_Fixtures/config_empty_modules.php';

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertEquals(InvalidConfigurationException::onMissingModules(), $exception);
    }

    /**
     * Jackson cannot build the objects when a required key is missing, and reports which
     * key it failed on. The dedicated messages for these cases belonged to a second loader
     * that was never wired up.
     */
    #[DataProvider('provideConfigsMissingARequiredKey')]
    public function test_given_a_config_when_a_required_key_is_missing_then_say_where(
        string $fixture,
        string $expectedLocation,
    ): void {
        $config = __DIR__ . "/_Fixtures/$fixture.php";

        $exception = $this->expectThrows(fn() => $this->loader->load($config));

        $this->assertInstanceOf(InvalidConfigurationException::class, $exception);
        $this->assertStringContainsString($expectedLocation, $exception->getMessage());
    }

    /**
     * @return array<string, array{fixture: string, expectedLocation: string}>
     */
    public static function provideConfigsMissingARequiredKey(): array
    {
        return [
            'When a module has no name, then' => [
                'fixture' => 'config_missing_module_name',
                'expectedLocation' => '$name',
            ],
            'When a module has no path, then' => [
                'fixture' => 'config_missing_module_path',
                'expectedLocation' => '$path',
            ],
            'When there is no modules key at all, then' => [
                'fixture' => 'config_missing_modules',
                'expectedLocation' => '$modules',
            ],
            'When output settings have no option, then' => [
                'fixture' => 'config_output_without_option',
                'expectedLocation' => '$option',
            ],
        ];
    }
}
