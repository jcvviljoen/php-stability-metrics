<?php

declare(strict_types=1);

namespace Stability\Tests\_Fixtures\Config;

use Stability\Config\OutputOption;
use Stability\Config\OutputSetting;

readonly class OutputSettingFactory
{
    public static function default(): OutputSetting
    {
        return new OutputSetting(
            OutputOption::CONSOLE,
            '',
            'stability-result',
        );
    }

    public static function json(): OutputSetting
    {
        return new OutputSetting(
            OutputOption::JSON,
            '',
            'stability-result',
        );
    }

    public static function console(): OutputSetting
    {
        return new OutputSetting(
            OutputOption::CONSOLE,
            '',
            'stability-result',
        );
    }
}
