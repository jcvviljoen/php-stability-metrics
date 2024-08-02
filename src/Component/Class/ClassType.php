<?php

declare(strict_types=1);

namespace Stability\Component\Class;

enum ClassType
{
    case ABSTRACT_CLASS;
    case CONCRETE_CLASS;
    case ENUM;
    case INTERFACE;
    case UNKNOWN;
}
