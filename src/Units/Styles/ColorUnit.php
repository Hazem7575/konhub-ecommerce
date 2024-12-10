<?php

namespace Konhub\Lido\Units\Styles;

class ColorUnit
{
    public static function rander($props, $type = 'background')
    {
        $style = '';

        if ($type == 'background') {
            if (isset($props['color'])) {
                $style .= 'background-color: ' . $props['color'] . '; ';
            }
        } elseif ($type == 'color') {
            if (isset($props['colors'])) {
                $style .= 'color: ' . $props['colors'][0] . '; ';
            }
        }

        return $style;
    }
}