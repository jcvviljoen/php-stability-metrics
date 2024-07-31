<?php

namespace Stability\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stability\Stability;
use Stability\Tests\_Fixtures\ConfigFactory;
use Stability\Tests\_Fixtures\SpyOutputWriter;
use Stability\Tests\_Fixtures\StabilityResultFactory;

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
