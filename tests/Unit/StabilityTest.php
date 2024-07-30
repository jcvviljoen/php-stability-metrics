<?php

namespace Instability\Tests\Unit;

use Instability\Stability;
use Instability\Tests\_Fixtures\ConfigFactory;
use PHPUnit\Framework\TestCase;

class StabilityTest extends TestCase
{
    private Stability $stability;

    protected function setUp(): void
    {
        parent::setUp();

        $config = ConfigFactory::stability();
        $this->stability = Stability::create($config);
    }

    public function test_check_stability(): void
    {
        $this->stability->calculate();
    }
}
