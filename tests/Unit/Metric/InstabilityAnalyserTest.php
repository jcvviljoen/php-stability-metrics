<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Metric;

use Override;
use PHPUnit\Framework\TestCase;
use Stability\Component\ComponentCollection;
use Stability\Metric\InstabilityAnalyser;
use Stability\Tests\_Fixtures\Component\ComponentFactory;
use Stability\Tests\_Fixtures\Metric\StabilityResultFactory;

class InstabilityAnalyserTest extends TestCase
{
    private InstabilityAnalyser $analyser;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $this->analyser = new InstabilityAnalyser();
    }

    public function test_given_components_then_calculate_stable_dependency_metric(): void
    {
        $components = new ComponentCollection([
            ComponentFactory::module1(),
            ComponentFactory::module2(),
            ComponentFactory::module3(),
        ]);

        $result = $this->analyser->analyse($components);

        $this->assertEquals(
            StabilityResultFactory::testSource(),
            $result,
        );
    }
}
