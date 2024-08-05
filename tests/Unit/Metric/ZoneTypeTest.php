<?php

declare(strict_types=1);

namespace Stability\Tests\Unit\Metric;

use PHPUnit\Framework\TestCase;
use Stability\Metric\ZoneType;

class ZoneTypeTest extends TestCase
{
    public function test_description(): void
    {
        $this->assertEquals(
            'Perfectly Balanced, as all things should be',
            ZoneType::USEFULNESS->description(),
        );

        $this->assertEquals(
            'Zone of Uselessness',
            ZoneType::USELESSNESS->description(),
        );

        $this->assertEquals(
            'Zone of Pain',
            ZoneType::PAIN->description(),
        );
    }

    public function test_icon(): void
    {
        $this->assertEquals(
            '🚀',
            ZoneType::USEFULNESS->icon(),
        );

        $this->assertEquals(
            '💩',
            ZoneType::USELESSNESS->icon(),
        );

        $this->assertEquals(
            '💀',
            ZoneType::PAIN->icon(),
        );
    }
}
