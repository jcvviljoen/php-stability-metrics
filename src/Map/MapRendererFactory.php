<?php

declare(strict_types=1);

namespace Stability\Map;

use Stability\Map\Renderers\DotMapRenderer;
use Stability\Map\Renderers\MermaidMapRenderer;

readonly class MapRendererFactory
{
    public static function create(MapOption $option): MapRenderer
    {
        return match ($option) {
            MapOption::MERMAID => new MermaidMapRenderer(),
            MapOption::DOT => new DotMapRenderer(),
        };
    }
}
