<?php

declare(strict_types=1);

namespace Stability\Application;

enum ConfigInitialisation
{
    case CREATED;
    case ALREADY_EXISTED;
}
