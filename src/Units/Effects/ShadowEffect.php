<?php

namespace Konhub\Lido\Units\Effects;

class ShadowEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            $offset = $props['effect']['settings']['offset'];
            $direction = $props['effect']['settings']['direction'];
            $blur = isset($props['effect']['settings']['blur']) ? $props['effect']['settings']['blur'] : 0;
            $transparency = $props['effect']['settings']['transparency'] / 100;
            $color = isset($props['effect']['settings']['color']) ? $props['effect']['settings']['color'] : $props['colors'][0];

            if (strpos($color, 'rgb') !== false) {
                $color = preg_replace('/[^\d,]/', '', $color);
            }

            $directionRadians = deg2rad($direction);
            $correctionFactor = 5.75;
            $xOffset = ($offset * cos($directionRadians)) / $correctionFactor;
            $yOffset = ($offset * sin($directionRadians)) / $correctionFactor;

            $style .= "text-shadow: rgba({$color}, {$transparency}) {$yOffset}px {$xOffset}px {$blur}px;";
        }

        return $style;
    }
}