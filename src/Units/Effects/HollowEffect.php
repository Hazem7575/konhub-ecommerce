<?php

namespace Konhub\Lido\Units\Effects;

class HollowEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            $fontSize = $props['fontSizes'][0];
            $thickness = $props['effect']['settings']['thickness'];
            $textStroke = 0.0091666 * $fontSize + 0.0008333 * ($fontSize * $thickness);
            $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : $props['colors'][0];
            
            $style .= "-webkit-text-stroke: {$textStroke}px {$color};";
            $style .= "-webkit-text-fill-color: transparent;";
        }

        return $style;
    }
}