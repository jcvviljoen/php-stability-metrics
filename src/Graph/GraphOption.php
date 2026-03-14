<?php

declare(strict_types=1);

namespace Stability\Graph;

enum GraphOption: string
{
    case MERMAID = 'mermaid';
    case DOT = 'dot';
}
