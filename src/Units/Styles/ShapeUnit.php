<?php

namespace Konhub\Lido\Units\Styles;

class ShapeUnit
{
    public static function rander($props)
    {
        return match ($props['shape']) {
            'circle' => 'clip-path: circle(50%);',
            'triangleUpsideDown' => 'clip-path: polygon(50% 100%, 0% 0%, 100% 0%);',
            default => '',
        };
    }
}