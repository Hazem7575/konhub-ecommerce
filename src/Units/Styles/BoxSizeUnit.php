<?php

namespace Konhub\Lido\Units\Styles;

class BoxSizeUnit
{
    public static function rander($props, $position = true)
    {
        $style = '';

        if (isset($props['width'])) {
            $style .= 'width: ' . $props['width'] . 'px; ';
        }

        if (isset($props['height'])) {
            $style .= 'height: ' . $props['height'] . 'px; ';
        }

        if ($position) {
            if (isset($props['x'])) {
                $style .= 'left: ' . $props['x'] . 'px; ';
            }

            if (isset($props['y'])) {
                $style .= 'top: ' . $props['y'] . 'px; ';
            }

            if (isset($props['x']) || isset($props['y'])) {
                $style .= 'position: absolute; ';
            }
        }

        return $style;
    }
}