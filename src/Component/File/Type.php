<?php

declare(strict_types=1);

namespace Stability\Component\File;

enum Type
{
    case ABSTRACT_CLASS;
    case CONCRETE_CLASS;
    case ENUM;
    case INTERFACE;
    case UNKNOWN;

    public function isAbstractClass(): bool
    {
        return $this === self::ABSTRACT_CLASS;
    }

    public function isConcreteClass(): bool
    {
        return $this === self::CONCRETE_CLASS;
    }

    public function isEnum(): bool
    {
        return $this === self::ENUM;
    }

    public function isInterface(): bool
    {
        return $this === self::INTERFACE;
    }

    public function isUnknown(): bool
    {
        return $this === self::UNKNOWN;
    }
}
