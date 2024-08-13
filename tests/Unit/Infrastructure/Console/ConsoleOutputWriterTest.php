<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\Console;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Stability\Component\ComponentResult;
use Stability\Infrastructure\Console\ConsoleOutputWriter;
use Stability\Tests\_Fixtures\StabilityResultFactory;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputWriterTest extends TestCase
{
    private ConsoleOutput&MockObject $consoleOutput;

    private ConsoleOutputWriter $consoleOutputWriter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->consoleOutput = $this->createMock(ConsoleOutput::class);

        $this->consoleOutputWriter = new ConsoleOutputWriter($this->consoleOutput);
    }

    public function test_output_result(): void
    {
        $result = StabilityResultFactory::testSource();
        $expects = count($result->componentResults);
        $expectedOutput = $this->generateExpectedOutput($result->componentResults);

        $this->consoleOutput
            ->expects($this->exactly($expects))
            ->method('writeln')
            ->willReturnCallback(
                fn (array $output) => $this->assertContains($output, $expectedOutput),
            );

        $this->consoleOutputWriter->outputResult($result);
    }

    /**
     * @param array<ComponentResult> $componentResults
     * @return array<string, string>
     */
    private function generateExpectedOutput(array $componentResults): array
    {
        $output = [];

        foreach ($componentResults as $componentResult) {
            $zoneDescription = $componentResult->zone->description();
            $zoneIcon = $componentResult->zone->icon();

            $resultOutput = [];

            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = "Component: {$componentResult->component->module->name}";
            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = "| Abstractness: $componentResult->abstractness";
            $resultOutput[] = "| Instability: $componentResult->instability";
            $resultOutput[] = "| DMS: $componentResult->dms";
            $resultOutput[] = "| Zone: $zoneIcon $zoneDescription";
            $resultOutput[] = '----------------------------------------';
            $resultOutput[] = '';

            $output[] = $resultOutput;
        }

        return $output;
    }
}
