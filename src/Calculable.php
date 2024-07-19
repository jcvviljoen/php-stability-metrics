<?php

namespace Instability;

/**
 * @template TResult
 */
interface Calculable // TODO some metrics might be calculable, others might need something else
{
    /**
     * @return TResult
     */
    public function calculate();
}
