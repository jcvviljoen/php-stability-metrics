<?php

namespace Instability\Exception;

class UndefinedConfigurationException extends InstabilityException
{
    public function output(): array
    {
        return [
            'Undefined configuration', // TODO is a formatter needed?
        ];
    }
}
