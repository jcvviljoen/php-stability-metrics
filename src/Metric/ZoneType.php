<?php

declare(strict_types=1);

namespace Stability\Metric;

enum ZoneType
{
    case USEFULNESS;
    case USELESSNESS;
    case PAIN;

    public function description(): string
    {
        return match ($this) {
            self::USEFULNESS => 'Perfectly Balanced, as all things should be',
            self::USELESSNESS => 'Zone of Uselessness',
            self::PAIN => 'Zone of Pain',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::USEFULNESS => '🚀',
            self::USELESSNESS => '💩',
            self::PAIN => '💀',
        };
    }
}
