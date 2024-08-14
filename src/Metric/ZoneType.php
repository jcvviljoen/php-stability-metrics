<?php

declare(strict_types=1);

namespace Stability\Metric;

enum ZoneType
{
    case USEFULNESS;
    case USELESSNESS;
    case PAIN;
    case PERFECT;

    public function description(): string
    {
        return match ($this) {
            self::USEFULNESS => 'Well-structured and useful',
            self::USELESSNESS => 'Zone of Uselessness',
            self::PAIN => 'Zone of Pain',
            self::PERFECT => 'Perfectly Balanced, as all things should be',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::USEFULNESS => '🚀',
            self::USELESSNESS => '💩',
            self::PAIN => '💀',
            self::PERFECT => '⚖️',
        };
    }
}
