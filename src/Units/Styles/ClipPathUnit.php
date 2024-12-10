<?php

namespace Konhub\Lido\Units\Styles;

class ClipPathUnit
{
    public static function rander($props)
    {
        $style = '';

        if (isset($props['clipPath'])) {
            if (strpos($props['clipPath'], 'path') !== 0) {
                return "clip-path: path('{$props['clipPath']}');";
            }

            $width = $props['boxSize']['width'] / $props['scale'];
            $height = $props['boxSize']['height'] / $props['scale'];
            $style .= 'clip-path: ' . self::convertToSquare($width, $height) . '; ';
        }
        return $style;
    }

    public static function getClipPathStyle($props)
    {
        $style = '';
        if (strpos($props['clipPath'], 'path') !== 0) {
            $style = "clip-path: path('{$props['clipPath']}');";
        }
        return $style;
    }

    private static function convertToSquare($width, $height)
    {
        return "path('M 0 0 L $width 0 L $width $height L 0 $height Z')";
    }
}