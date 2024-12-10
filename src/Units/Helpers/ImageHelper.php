<?php

namespace Konhub\Lido\Units\Helpers;

use Konhub\Lido\Traits\RenderDomCss;
use Konhub\Lido\Traits\RenderDomJS;
use Konhub\Lido\Services\Json2HtmlConverter;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\FilterUnit;
use Konhub\Lido\Units\Styles\OpacityUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\ZindexUnit;

class ImageHelper
{
    use RenderDomJS, RenderDomCss;

    public static function getAttr($element, $parent, $is_root = false)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= 'width: 100%;height: 100%;';
        $styleImage .= FilterUnit::rander($element);
        $styleImage .= ZindexUnit::rander(1);

        $style_render = Json2HtmlConverter::ImageStyleListen($styleImage, $element, $parent);

        if ($is_root) {
            $styleImage .= 'width:100%;position: absolute;';
        }

        $parent_style = BoxSizeUnit::rander($element['boxSize']);
        $parent_style .= 'position: relative;';
        if (isset($element['position'])) {
            $parent_style .= 'transform: translate(' . $element['position']['x'] . 'px, ' . $element['position']['y'] . 'px);';
        }

        return [
            'type' => 'img',
            'is_root' => $is_root,
            'parent' => [
                'style' => $parent_style
            ],
            'attr' => [
                'class' => $style_render['class'],
                'src' => $element['url'],
                'style' => $styleImage,
            ]
        ];
    }

    public static function render($element)
    {
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }
        $html = '<img ' . trim($attrs) . '>';
        if (isset($element['parent']) && !$element['is_root']) {
            return '<div style="' . $element['parent']['style'] . '">' . $html . '</div>';
        } else {
            return $html;
        }
    }
}