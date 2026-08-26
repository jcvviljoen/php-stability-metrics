<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Output\Writers;

use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Metric\ComponentMetric;
use Stability\Output\Writers\ConsoleOutputWriter;
use Stability\Tests\_Fixtures\Metric\StabilityResultFactory;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputWriterTest extends TestCase
{
    private ConsoleOutput&MockObject $consoleOutput;

    private ConsoleOutputWriter $consoleOutputWriter;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->consoleOutput = $this->createMock(ConsoleOutput::class);

        $this->consoleOutputWriter = new ConsoleOutputWriter($this->consoleOutput);
    }

    public function test_output_result(): void
    {
        $result = StabilityResultFactory::testSource();
        $expects = count($result->stableDependencyMetrics);
        $expectedOutput = $this->generateExpectedOutput($result->stableDependencyMetrics);

        $this->consoleOutput
            ->expects($this->exactly($expects))
            ->method('writeln')
            ->willReturnCallback(
                fn (array $output) => $this->assertContains($output, $expectedOutput),
            );

        $this->consoleOutputWriter->outputResult($result);
    }

    /**
     * @param array<ComponentMetric> $componentResults
     * @return list<array<int, string>>
     */
    private function generateExpectedOutput(array $componentResults): array
    {
        $output = [];

        foreach ($componentResults as $componentResult) {
            $zoneDescription = $componentResult->zone->description();
            $zoneIcon = $componentResult->zone->icon();

            $resultOutput = [];

            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = "Component: {$componentResult->componentName}";
            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = '| Abstractness: ' . number_format($componentResult->abstractness(), 2);
            $resultOutput[] = '| Instability: ' . number_format($componentResult->instability(), 2);
            $resultOutput[] = '| DMS: ' . number_format($componentResult->dms(), 2);
            $resultOutput[] = "| Zone: $zoneIcon $zoneDescription";
            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = '';

            $output[] = $resultOutput;
        }

        return $output;
    }
}
