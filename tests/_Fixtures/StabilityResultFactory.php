<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures;

use Stability\StabilityResult;
use Stability\Tests\_Fixtures\Component\ComponentResultFactory;

class StabilityResultFactory
{
    public static function fixtureSource(): StabilityResult
    {
        return new StabilityResult([
            ComponentResultFactory::module1(),
            ComponentResultFactory::module2(),
            ComponentResultFactory::module3(),
        ]);
    }
}
