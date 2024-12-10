<?php

namespace Konhub\Lido\Units\Styles;

class PositionUnit
{
    public static function rander($props)
    {
        $style = '';
        if (isset($props['x'])) {
            if ($props['x'] < 0) {
                $style .= 'left: ' . $props['x'] . 'px; ';
            } else {
                $style .= 'left: ' . 0 . 'px; ';
            }
        }
        if (isset($props['y'])) {
            if ($props['y'] < 0) {
                $style .= 'top: ' . $props['y'] . 'px; ';
            } else {
                $style .= 'top: ' . 0 . 'px; ';
            }
        }
        $style .= 'position: absolute;';
        return $style;
    }
}