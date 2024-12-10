<?php

namespace Konhub\Lido\Units\Styles;

class FilterUnit
{
    public static function rander($props)
    {
        $style = '';
        $filter = [];

        if (isset($props['hueRotate']) && $props['hueRotate'] != 0) {
            $filter[] = 'hue-rotate(' . $props['hueRotate'] . 'deg)';
        }
        if (isset($props['grayscale']) && $props['grayscale'] != 0) {
            $filter[] = 'grayscale(' . $props['grayscale'] . '%)';
        }
        if (isset($props['blur']) && $props['blur'] != 0) {
            $filter[] = 'blur(' . $props['blur'] . 'px)';
        }

        if (!empty($filter)) {
            $style .= 'filter: ' . implode(' ', $filter) . ';';
        }

        return $style;
    }
}