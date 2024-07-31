<?php

namespace Instability\Tests\Unit;

use Instability\Stability;
use Instability\Tests\_Fixtures\ConfigFactory;
use Instability\Tests\_Fixtures\SpyOutputWriter;
use Instability\Tests\_Fixtures\StabilityResultFactory;
use PHPUnit\Framework\TestCase;

class StabilityTest extends TestCase
{
    private SpyOutputWriter $outputWriter;

    private Stability $stability;

    protected function setUp(): void
    {
        parent::setUp();

        $config = ConfigFactory::stability();

        $this->outputWriter = new SpyOutputWriter();

        $this->stability = Stability::create($config, $this->outputWriter);
    }

    public function test_calculate_stability(): void
    {
        $expectedResult = StabilityResultFactory::unitStability();

        $this->stability->calculate();

        $this->outputWriter->verifyIsWritten($expectedResult);
    }
}
