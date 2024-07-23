<?php

namespace Instability\Tests\_Fixtures;

use Instability\Config\Config;

class ConfigFactory
{
    public static function fixture(): Config
    {
        $config = [
            'basePath' => '../_Fixtures',
            'modules' => [
                [
                    'module' => 'Data',
                ],
            ],
        ];

        return Config::from($config);
    }
}
