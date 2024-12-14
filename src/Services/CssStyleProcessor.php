<?php

namespace Konhub\Lido\Services;

class CssStyleProcessor
{
    public static function processStyle(string $style, float $scale, ?string $maxWidth = null, int $layerWidth = 0): string
    {
        // Don't scale width if it matches the layer size
        if ($layerWidth > 0 && strpos($style, "width: {$layerWidth}px") !== false) {
            return $style;
        }

        // Process pixel values
        $processedStyle = preg_replace_callback(
            '/(\d+\.?\d*)px/',
            function($matches) use ($scale, $maxWidth, $style) {
                $value = $matches[1];
                // Don't scale width on mobile
                if ($maxWidth && (int)$maxWidth <= 768 && strpos($style, 'width:') !== false) {
                    return $value . 'px';
                }
                return round($value * $scale, 2) . 'px';
            },
            $style
        );

        return self::applyMobileAdjustments($processedStyle, $maxWidth);
    }

    private static function applyMobileAdjustments(string $style, ?string $maxWidth): string
    {
        if ($maxWidth && (int)$maxWidth <= 768) {
            // Remove grid-template and position properties
            $style = preg_replace('/grid-template[^;]+;/', '', $style);
            $style = str_replace('position: absolute', 'position: relative', $style);
            $style = preg_replace('/grid-area[^;]+;/', '', $style);

            // Ensure proper width and display
            if (strpos($style, 'width:') === false) {
                $style .= 'width: 100%;';
            }
            if (strpos($style, 'display:') === false) {
                $style .= 'display: block;';
            }
        }

        return $style;
    }
}
