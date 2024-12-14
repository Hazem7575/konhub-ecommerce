<?php

namespace Konhub\Lido\Services;

use Konhub\Lido\Managers\NestedElementsManager;
use Konhub\Lido\Managers\ResponsiveManager;
use Konhub\Lido\Traits\RenderDomCss;
use Konhub\Lido\Traits\RenderDomFiles;
use Konhub\Lido\Traits\RenderDomFonts;
use Konhub\Lido\Traits\RenderDomJS;
use Konhub\Lido\Units\Helpers\RenderElement;
use Konhub\Lido\Units\Layers\FrameLayer;
use Konhub\Lido\Units\Layers\GroupLayer;
use Konhub\Lido\Units\Layers\ImageLayer;
use Konhub\Lido\Units\Layers\LineLayer;
use Konhub\Lido\Units\Layers\RootShape;
use Konhub\Lido\Units\Layers\ShapeLayer;
use Konhub\Lido\Units\Layers\SvgLayer;
use Konhub\Lido\Units\Layers\TextLayer;
use Konhub\Lido\Units\Styles\GridUnit;
use Konhub\Lido\Exceptions\LidoException;
use Illuminate\Support\Collection;

class Json2HtmlConverter
{
    use RenderDomFonts, RenderDomFiles, RenderDomCss, RenderDomJS;

    protected static $template;
    protected static $paths_dir;
    protected static $size_layer;
    protected static $width_layers = [];
    protected static $fonts = [];
    protected static $fonts_css = '';

    private $elementManager;
    private $styleGenerator;
    private $nestedElementsManager;
    private $responsiveManager;

    public function __construct()
    {
//        $this->elementManager = new ElementManager();
//        $this->styleGenerator = new StyleGenerator();
//        $this->nestedElementsManager = new NestedElementsManager();
//        $this->responsiveManager = new ResponsiveManager();

        self::$paths_dir = config('lido.paths', [
            'path_css' => '/test/css/',
            'path_js' => '/test/js/',
            'path_font' => '/test/fonts',
        ]);

    }

    public function convert($json)
    {
        if (!is_array($json)) {
            $json = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw LidoException::invalidJson();
            }
        }

        $html = '';
        foreach ($json as $key => $value) {
            if (!isset($value['layers']) || !isset($value['layers']['ROOT'])) {
                throw LidoException::missingRequiredField('layers.ROOT');
            }

            $first = $value['layers'];
            $root = $first['ROOT'];
            self::$template = $first;
            self::getFonts()->getFontsUrl()->getRenderFonts()->render();

            $width = $root['props']['boxSize']['width'];
            $height = $root['props']['boxSize']['height'];
            self::$size_layer = [
                'width' => $width,
                'height' => $height,
            ];

            // Set layer size in CssStateManager
            CssStateManager::getInstance()->setLayerSize(self::$size_layer);

            $html .= self::buildRoot($root, 'ROOT', $first);
        }

        self::renderFileCss();

        return [
            'html' => $html,
            'css' => self::getUrlCss(),
            'js' => self::getUrlJs(),
            'sizes' => self::$size_layer
        ];
    }

    public static function buildRoot($child, $index, &$collection)
    {

        $is_root = $child['type']['resolvedName'] == 'RootLayer';
        $style = self::shaps($child, $collection);

        if (!isset($style['style'])) {
            return '';
        }

        $classes = 'layer-contianer ';
        $html = '<section class="' . $classes . '">';

        $class_name = self::css_name($style['style'] . "display: grid;position: relative;grid-area: 1 / 2 / 2 / 3;z-index:2");
        self::put_size($style['style'], $class_name, $child);

        if (isset($style['children']) && is_array($style['children']) && count($style['children']) > 0) {
            foreach ($style['children'] as $child) {
                $html .= RenderElement::render($child);
            }
        }


        $html .= '<div class="' . $class_name . '">';

        $check_if_have_child = collect($collection)->where('parent', '=', $index)->all();
        if ($check_if_have_child && count($check_if_have_child) > 0) {
            $zIndex = 1;
            foreach ($check_if_have_child as $row => $child_sub) {
                $html .= self::children($child_sub, $row, $collection, $zIndex);
                $zIndex += 1;
            }
        }

        $html .= '</div>';
        $html .= '</section>';

        return $html;
    }

    public static function children($child, $index, &$collection, $zIndex)
    {
        $style = self::shaps($child, $collection);

        if (!isset($style['style'])) {
            return '';
        }

        $styleWithGridDiv = 'position: relative;z-index: ' . $zIndex . ';';
        $style_1 = '';
        if (isset($style['grid'])) {
            $style_1 = $style['grid'];
        }

        $class_name = self::css_name($style_1 . $styleWithGridDiv);
        $class_name2 = self::css_name($style['style'] . "");

        self::put_size($style_1, $class_name, $child);
        self::put_size($style['style'], $class_name2, $child);

        $html = '<div data-element-id="' . $index . '" class="' . $class_name . '">';
        $html .= '<div class="' . $class_name2 . '">';

        if (isset($style['children']) && is_array($style['children']) && count($style['children']) > 0) {
            foreach ($style['children'] as $child) {
                $html .= RenderElement::render($child);
            }
        }

        $check_if_have_child = collect($collection)->where('parent', '=', $index)->all();
        if ($check_if_have_child && count($check_if_have_child) > 0) {
            $zIndex = 1;
            foreach ($check_if_have_child as $row => $child_sub) {
                $html .= self::children($child_sub, $row, $collection, $zIndex);
                $zIndex += 1;
            }
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public static function shaps($childElement, &$collection)
    {
        $type = $childElement['type']['resolvedName'];
        $props = $childElement['props'];

        if ($type == 'TextLayer' && isset($props['fonts']) && count($props['fonts']) > 0) {
            foreach ($props['fonts'] as $font) {
                $fontName = $font['name'];
                if (!isset(self::$fonts[$fontName])) {
                    self::$fonts[$fontName] = [];
                }
                foreach ($font['fonts'] as $fontVariant) {
                    if (isset($fontVariant['urls'])) {
                        foreach ($fontVariant['urls'] as $url) {
                            self::$fonts[$fontName][] = [
                                'url' => $url,
                                'style' => $fontVariant['style'] ?? 'normal'
                            ];
                        }
                    }
                }
            }
        }

        $style = match ($type) {
            'RootLayer' => RootShape::rander($props),
            'ShapeLayer' => ShapeLayer::rander($props),
            'FrameLayer' => FrameLayer::rander($props),
            'TextLayer' => TextLayer::rander($props),
            'GroupLayer' => GroupLayer::rander($props),
            'SvgLayer' => SvgLayer::rander($props),
            'ImageLayer' => ImageLayer::rander($props),
            'LineLayer' => LineLayer::rander($props),
            default => throw LidoException::invalidLayerType($type),
        };

        if (isset($childElement['child'])) {
            $style['style'] = $style['style'] . GridUnit::rander($childElement, $collection);
        }

        return $style;
    }

    public static function ImageStyleListen($styleImage, $element, $parent = null)
    {
        $class_name = self::css_name($styleImage);
        if (!isset($parent) || !isset($parent['clipPath'])) {
            self::put_size($styleImage, $class_name, $element);
        }

        return [
            'class' => $class_name
        ];
    }


    protected function processElements($elements)
    {
        $sortedElements = ElementOrderManager::sortElementsByPosition($elements);

        foreach ($sortedElements as $element) {
            $this->processElement($element);
        }
    }
}
