<?php

namespace Instability\Tests\_Fixtures;

use Instability\StabilityResult;

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
