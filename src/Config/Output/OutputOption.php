<?php

declare(strict_types=1);

namespace Stability\Config\Output;

enum OutputOption: string
{
    case CONSOLE = 'console';
    case JSON = 'json';
}
