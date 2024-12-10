<?php

namespace Konhub\Lido\Traits;

use Konhub\Lido\Services\CssFormatter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait RenderDomCss
{
    protected static $collection_css = [];
    protected static $render_css_collection = '';
    protected static $mediaQuery = [
        [
            'max-width' => '375px',
            'scale' => 0.3,
        ],
        [
            'min-width' => '375.05px',
            'max-width' => '480px',
            'scale' => 0.4,
        ],
        [
            'min-width' => '480.05px',
            'max-width' => '768px',
            'scale' => 0.6,
        ],
        [
            'min-width' => '768.05px',
            'max-width' => '1024px',
            'scale' => 0.8,
        ],
        [
            'min-width' => '1024.05px',
            'scale' => 1,
        ]
    ];

    protected static function css_name($style, $prefix = null, $name = null, $is_class = true)
    {
        if (is_null($name)) {
            $name = 'store_' . Str::random(8);
        }

        $name = $prefix . $name;

        self::$collection_css[$name] = [
            'is_class' => $is_class,
            'style' => $style,
        ];

        return $name;
    }

    protected static function FirstRenderCss()
    {
        self::$render_css_collection = '
            @media (prefers-reduced-motion: reduce) {
                .animated {
                    animation: none !important;
                }
            }
            :root {
               --layer-size: ' . self::$size_layer['width'] . 'px;
            }
            .layer-contianer {
               width: 100%;
               max-width: var(--layer-size);
               margin: 0 auto;
               overflow: hidden;
            }
            html {
                -webkit-text-size-adjust: 100%;
                scroll-behavior: smooth;
            }
            body,
            html,
            p,
            ul,
            ol,
            li {
                margin: 0;
                padding: 0;
                font-synthesis: none;
                font-kerning: none;
                font-variant-ligatures: none;
                font-feature-settings: "kern" 0, "calt" 0, "liga" 0, "clig" 0, "dlig" 0, "hlig" 0;
                font-family: unset;
                -webkit-font-smoothing: subpixel-antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: geometricprecision;
                white-space: normal;
            }
            * {
                box-sizing: border-box;
            }
            img {
                max-width: 100%;
                height: auto;
            }
        ';
    }

    protected static function RenderCollectionCss()
    {
        self::FirstRenderCss();
        self::RenderCssMediaQuery();
        self::$template_css .= self::$render_css_collection;
    }

    protected static function RenderCssMediaQuery()
    {
        foreach (self::$mediaQuery as $mediaQuery) {
            $html_media = '';
            if (isset($mediaQuery['min-width'])) {
                $html_media .= '(min-width: ' . $mediaQuery['min-width'] . ')';
            }
            if (isset($mediaQuery['max-width'])) {
                if (isset($mediaQuery['min-width'])) {
                    $html_media .= ' and ';
                }
                $html_media .= '(max-width: ' . $mediaQuery['max-width'] . ')';
            }
            self::$render_css_collection .= '@media ' . $html_media . ' {';
            self::$render_css_collection .= self::ResponsiveCss($mediaQuery);
            self::$render_css_collection .= '}';
        }
    }

    protected static function ResponsiveCss($mediaQuery)
    {
        $styles = '';
        if (is_array(self::$collection_css) && count(self::$collection_css) > 0) {
            foreach (self::$collection_css as $key => $collect) {
                $prefix = $collect['is_class'] ? '.' : '#';
                $style_media = self::RenderMediaCss($collect['style'], $mediaQuery['scale']);
                $styles .= $prefix . $key . '{' . $style_media . '}';
            }
        }
        return $styles;
    }

    protected static function RenderMediaCss($style, $scale)
    {
        // Scale pixel values
        $style = preg_replace_callback(
            '/(\d+\.?\d*)px/',
            function($matches) use ($scale) {
                return round($matches[1] * $scale, 2) . 'px';
            },
            $style
        );

        // Scale rem values in grid templates
        $style = preg_replace_callback(
            '/grid-template-columns:\s*([^;]+);/',
            function($matches) use ($scale) {
                $columns = explode(' ', $matches[1]);
                $scaledColumns = array_map(function($col) use ($scale) {
                    return (floatval($col) * $scale) . 'rem';
                }, $columns);
                return 'grid-template-columns: ' . implode(' ', $scaledColumns) . ';';
            },
            $style
        );

        $style = preg_replace_callback(
            '/grid-template-rows:\s*([^;]+);/',
            function($matches) use ($scale) {
                $rows = explode(' ', $matches[1]);
                $scaledRows = array_map(function($row) use ($scale) {
                    if (strpos($row, 'minmax') !== false) {
                        return preg_replace_callback(
                            '/minmax\(([\d.]+)rem/',
                            function($matches) use ($scale) {
                                return 'minmax(' . (floatval($matches[1]) * $scale) . 'rem';
                            },
                            $row
                        );
                    }
                    return $row;
                }, $rows);
                return 'grid-template-rows: ' . implode(' ', $scaledRows) . ';';
            },
            $style
        );

        return $style;
    }
}
