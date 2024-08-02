<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures;

use PHPUnit\Framework\Assert;
use Stability\OutputWriter;
use Stability\StabilityResult;

class SpyOutputWriter implements OutputWriter
{
    /**
     * @var array<StabilityResult>
     */
    private array $writtenResults = [];

    public function outputResult(StabilityResult $result): void
    {
        $this->writtenResults[] = $result;
    }

    public function verifyIsWritten(StabilityResult $result): void
    {
        Assert::assertTrue(
            in_array($result, $this->writtenResults),
            'Failed asserting that the stability result was written to the output writer',
        );
    }
}
