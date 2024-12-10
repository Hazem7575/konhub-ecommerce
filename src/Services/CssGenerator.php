<?php

namespace Konhub\Lido\Services;

class CssGenerator
{
    private $mediaQueries;
    private $baseStyles;
    private $layerSize;

    public function __construct(array $mediaQueries, array $layerSize)
    {
        $this->mediaQueries = $mediaQueries;
        $this->layerSize = $layerSize;
        $this->baseStyles = $this->generateBaseStyles();
    }

    public function generate(array $styles): string
    {
        $css = $this->baseStyles;

        foreach ($this->mediaQueries as $query) {
            $css .= $this->generateMediaQuery($query, $styles);
        }

        return $css;
    }

    private function generateBaseStyles(): string
    {
        return '
            :root {
               --layer-size: ' . $this->layerSize['width'] . 'px;
               --mobile-breakpoint: 768px;
               --container-padding: 1rem;
            }

            .layer-container {
               width: 100%;
               max-width: var(--layer-size);
               margin: 0 auto;
               padding: var(--container-padding);
               overflow: hidden;
               display: grid;
               gap: 1rem;
               grid-auto-flow: dense;
            }

            [class*="store_"] {
                transition: transform 0.3s ease, width 0.3s ease, height 0.3s ease;
                max-width: 100%;
            }

            .responsive-grid {
                display: grid;
                gap: 1rem;
                width: 100%;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            @media (max-width: 768px) {
                .layer-container {
                    grid-template-columns: 1fr !important;
                }

                [class*="store_"] {
                    position: relative !important;
                    left: auto !important;
                    top: auto !important;
                    width: 100% !important;
                    transform: none !important;
                }
            }
        ';
    }

    private function generateMediaQuery(array $query, array $styles): string
    {
        $conditions = [];

        if (isset($query['min-width'])) {
            $conditions[] = "(min-width: {$query['min-width']})";
        }
        if (isset($query['max-width'])) {
            $conditions[] = "(max-width: {$query['max-width']})";
        }

        $mediaQuery = '@media ' . implode(' and ', $conditions) . ' {';
        $mediaQuery .= $this->generateResponsiveStyles($styles, $query);
        $mediaQuery .= '}';

        return $mediaQuery;
    }

    private function generateResponsiveStyles(array $styles, array $query): string
    {
        $css = '';
        $scale = $this->getScaleForBreakpoint($query);

        foreach ($styles as $selector => $properties) {
            $css .= $this->generateSelectorStyles($selector, $properties, $scale);
        }

        return $css;
    }

    private function generateSelectorStyles(string $selector, array $properties, float $scale): string
    {
        $prefix = $properties['is_class'] ? '.' : '#';
        $style = $this->applyResponsiveTransforms($properties['style'], $scale);
        return $prefix . $selector . '{' . $style . '}';
    }

    private function applyResponsiveTransforms(string $style, float $scale): string
    {
        // Scale pixel values
        $style = preg_replace_callback(
            '/(\d+\.?\d*)px/',
            function($matches) use ($scale) {
                $value = round(floatval($matches[1]) * $scale, 2);
                return "min({$value}px, 100%)";
            },
            $style
        );

        // Handle grid properties
        if (strpos($style, 'grid-template-columns') !== false) {
            $style = preg_replace(
                '/grid-template-columns:[^;]+;/',
                'grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));',
                $style
            );
        }

        return $style;
    }

    private function getScaleForBreakpoint(array $query): float
    {
        if (isset($query['max-width'])) {
            $maxWidth = (int)$query['max-width'];
            if ($maxWidth <= 375) return 0.5;
            if ($maxWidth <= 768) return 0.7;
            if ($maxWidth <= 1024) return 0.85;
        }
        return 1;
    }
}
