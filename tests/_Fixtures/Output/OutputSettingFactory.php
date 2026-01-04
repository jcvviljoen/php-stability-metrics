<?php

namespace Stability\Tests\_Fixtures\Output;

use Stability\Output\OutputOption;
use Stability\Output\OutputSetting;

readonly class OutputSettingFactory
{
    public static function default(): OutputSetting
    {
        return new OutputSetting(
            OutputOption::JSON,
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