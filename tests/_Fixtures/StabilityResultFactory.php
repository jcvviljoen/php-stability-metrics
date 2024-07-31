<?php

namespace Stability\Tests\_Fixtures;

use Stability\StabilityResult;

class StabilityResultFactory
{
    public static function unitStability(): StabilityResult
    {
        return new StabilityResult([
            ComponentResultFactory::module1(),
            ComponentResultFactory::module2(),
            ComponentResultFactory::module3(),
        ]);
    }
}
