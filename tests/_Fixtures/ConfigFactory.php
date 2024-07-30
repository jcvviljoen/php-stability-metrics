<?php

namespace Instability\Tests\_Fixtures;

use Instability\Config\Config;

class ConfigFactory
{
    public static function stability(): Config
    {
        $config = [
            'basePath' => '_Fixtures/Stability',
            'modules' => [
                ['module' => 'Module1'],
                ['module' => 'Module2'],
                ['module' => 'Module3'],
            ],
        ];

        return Config::from($config);
    }
}
