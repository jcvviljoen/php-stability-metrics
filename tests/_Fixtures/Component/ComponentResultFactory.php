<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Component;

use Stability\Component\ComponentResult;

class ComponentResultFactory
{
    public static function module1(): ComponentResult
    {
        return new ComponentResult(
            ComponentFactory::module1(),
            0.6666666666666666,
            0.5,
            0.16666666666666652,
        );
    }

    public static function module2(): ComponentResult
    {
        return new ComponentResult(
            ComponentFactory::module2(),
            0.5,
            1,
            0.5,
        );
    }

    public static function module3(): ComponentResult
    {
        return new ComponentResult(
            ComponentFactory::module3(),
            0,
            0,
            1,
        );
    }
}
