<?php

namespace Konhub\Lido\Units\Styles;

class GridElementUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['grid']['column-start'], $props['grid']['column-end'], $props['grid']['row-start'], $props['grid']['row-end'])) {
            $style .= 'grid-area: ' . $props['grid']['row-start'] . ' / ' . $props['grid']['column-start'] . ' / ' . $props['grid']['row-end'] . ' / ' . $props['grid']['column-end'] . ';';
        }

        return $style;
    }
}