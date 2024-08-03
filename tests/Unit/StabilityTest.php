<?php

declare(strict_types=1);

namespace Stability\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Component\ComponentParser;
use Stability\Config\ConfigLoader;
use Stability\Stability;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Config\ConfigFactory;
use Stability\Tests\_Fixtures\SpyOutputWriter;
use Stability\Tests\_Fixtures\StabilityResultFactory;

class StabilityTest extends TestCase
{
    private ConfigLoader&MockObject $configLoader;
    private ComponentParser&MockObject $componentParser;
    private SpyOutputWriter $outputWriter;

    private Stability $stability;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configLoader = $this->createMock(ConfigLoader::class);
        $this->componentParser = $this->createMock(ComponentParser::class);
        $this->outputWriter = new SpyOutputWriter();

        $this->stability = new Stability(
            $this->componentParser,
            $this->configLoader,
            $this->outputWriter,
        );
    }

    public function test_calculate_stability(): void
    {
        $configPath = 'config.php';
        $this->setupLoadConfig();

        $this->expectToParseModules();
        $this->stability->calculate($configPath);

        $this->outputWriter->verifyIsWritten(StabilityResultFactory::testSource());
    }

    private function setupLoadConfig(): void
    {
        $this->configLoader
            ->expects($this->once())
            ->method('load')
            ->with('config.php')
            ->willReturn(ConfigFactory::testSource());
    }

    private function expectToParseModules(): void
    {
        $this->componentParser
            ->expects($this->exactly(3))
            ->method('parse')
            ->willReturnOnConsecutiveCalls(
                ComponentFactory::module1(),
                ComponentFactory::module2(),
                ComponentFactory::module3(),
            );
    }
}
