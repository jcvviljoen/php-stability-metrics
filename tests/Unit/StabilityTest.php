<?php

declare(strict_types=1);

namespace Stability\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Stability\Stability;
use Stability\Tests\_Fixtures\SpyOutputWriter;
use Stability\Tests\_Fixtures\StabilityResultFactory;

class StabilityTest extends TestCase
{
    private SpyOutputWriter $outputWriter;

    private Stability $stability;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outputWriter = new SpyOutputWriter();
    }

    public function test_calculate_stability(): void
    {
        $this->markTestSkipped('Will be re-implemented');
    }
}
