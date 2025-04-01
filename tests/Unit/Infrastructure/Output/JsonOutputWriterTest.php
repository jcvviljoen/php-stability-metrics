<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Infrastructure\Output;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stability\Infrastructure\Output\JsonOutputWriter;
use Stability\Tests\_Fixtures\StabilityResultFactory;
use stdClass;

class JsonOutputWriterTest extends TestCase
{
    private const string PATH_RESULT = __DIR__ . '/_Fixtures/Files/stability-result.json';

    private JsonOutputWriter $jsonOutputWriter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonOutputWriter = new JsonOutputWriter(self::PATH_RESULT);
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

    protected function tearDown(): void
    {
        // Always reset to an empty object in the JSON file after test has executed.
        file_put_contents(
            self::PATH_RESULT,
            json_encode(new stdClass(), JSON_PRETTY_PRINT),
        );

        parent::tearDown();
    }
}
