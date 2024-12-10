<?php

namespace Konhub\Lido\Units\Effects;

class LiftEffect
{
    public static function render($props)
    {
        $style = '';

        if (isset($props['effect'])) {
            $intensity = $props['effect']['settings']['intensity'];
            $color = 'rgb(0, 0, 0)';

            if (strpos($color, 'rgb') !== false) {
                $color = preg_replace('/[^\d,]/', '', $color);
            }

            $alpha = 0.0055 * $intensity;
            $blurRadius = 4.875 + (0.0625 * $intensity);

            $style .= "text-shadow: rgba({$color}, {$alpha}) 0px 4.75px {$blurRadius}px;";
        }

        return $style;
    }
}