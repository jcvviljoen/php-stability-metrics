<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures;

use PHPUnit\Framework\Assert;
use Stability\Metric\Result;
use Stability\Output\OutputWriter;

class SpyOutputWriter implements OutputWriter
{
    /**
     * @var array<Result>
     */
    private array $writtenResults = [];

    public function outputResult(Result $result): void
    {
        $this->writtenResults[] = $result;
    }

    public function verifyIsWritten(Result $result): void
    {
        Assert::assertTrue(
            in_array($result, $this->writtenResults),
            'Failed asserting that the stability result was written to the output writer',
        );
    }
}
