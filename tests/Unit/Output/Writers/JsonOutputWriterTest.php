<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Output\Writers;

use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stability\Output\OutputOption;
use Stability\Output\OutputSetting;
use Stability\Output\Writers\JsonOutputWriter;
use Stability\Tests\_Fixtures\Metric\StabilityResultFactory;
use stdClass;

class JsonOutputWriterTest extends TestCase
{
    private const string PATH_RESULT = __DIR__ . '/_Fixtures/stability-result.json';

    private JsonOutputWriter $jsonOutputWriter;

    #[Override] protected function setUp(): void
    {
        parent::setUp();

        $outputSetting = new OutputSetting(
            OutputOption::JSON,
            __DIR__ . '/_Fixtures',
            'stability-result',
        );

        $this->jsonOutputWriter = new JsonOutputWriter($outputSetting);
    }

    #[Test] public function output_result(): void
    {
        $result = StabilityResultFactory::testSource();

        $this->jsonOutputWriter->outputResult($result);

        $this->assertJsonStringEqualsJsonFile(
            self::PATH_RESULT,
            json_encode($result, JSON_PRETTY_PRINT),
        );
    }

    #[Override] protected function tearDown(): void
    {
        // Always reset to an empty object in the JSON file after test has executed.
        file_put_contents(
            self::PATH_RESULT,
            json_encode(new stdClass(), JSON_PRETTY_PRINT),
        );

        parent::tearDown();
    }
}
