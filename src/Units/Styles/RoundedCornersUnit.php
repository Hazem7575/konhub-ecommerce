<?php

namespace Konhub\Lido\Units\Styles;

class RoundedCornersUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['roundedCorners'])) {
            $style .= 'border-radius: ' . ($props['roundedCorners'] / 3) . 'px; ';
        }
        return $style;
    }
}