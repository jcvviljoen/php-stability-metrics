<?php

namespace Instability;

readonly abstract class Metric
{
    private function __construct()
    {
    }

    public static function create(): static
    {
        return new static();
    }
}
