<?php

namespace Konhub\Lido\Units\Styles;

class TransformUnit
{
    public static function rander($props, $withScale = false)
    {
        $style = '';
        $transform = [];

        if (isset($props['rotate'])) {
            $transform[] = 'rotate(' . $props['rotate'] . 'deg)';
        }
        if ($withScale && isset($props['scale'])) {
            $transform[] = 'scale(' . $props['scale'] . ')';
        }

        if (!empty($transform)) {
            $style .= 'transform: ' . implode(' ', $transform) . '; ';
        }
        return $style;
    }
}