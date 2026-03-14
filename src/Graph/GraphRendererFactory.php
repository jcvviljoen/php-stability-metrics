<?php

declare(strict_types=1);

namespace Stability\Graph;

use Stability\Graph\Renderers\DotGraphRenderer;
use Stability\Graph\Renderers\MermaidGraphRenderer;

readonly class GraphRendererFactory
{
    public static function create(GraphOption $option): GraphRenderer
    {
        return match ($option) {
            GraphOption::MERMAID => new MermaidGraphRenderer(),
            GraphOption::DOT => new DotGraphRenderer(),
        };
    }
}
