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
}
