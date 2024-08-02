<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\Console;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
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
        $result = StabilityResultFactory::fixtureSource();
        $expects = count($result->componentResults);

        $this->consoleOutput
            ->expects($this->exactly($expects))
            ->method('writeln')
            ->with($this->isType('array'));

        $this->consoleOutputWriter->outputResult($result);
    }
}
