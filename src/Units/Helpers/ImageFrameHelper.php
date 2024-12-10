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

class ImageFrameHelper
{
    use RenderDomJS, RenderDomCss;

    public static function getAttr($element, $parent, $is_root = false)
    {
        $styleImage = '';

        $styleImage .= OpacityUnit::rander($element);
        $styleImage .= BoxSizeUnit::rander($element['boxSize']);
        $styleImage .= PositionUnit::rander($element['position']);
        $styleImage .= FilterUnit::rander($element);
        $styleImage .= ZindexUnit::rander(1);

        $style_render = Json2HtmlConverter::ImageStyleListen($styleImage, $element, $parent);
        if (isset($element['flipHorizontal'])) {
            $styleImage .= 'transform: scaleX(-1);';
        }
        if ($is_root) {
            $styleImage .= 'width:100%';
        }

        return [
            'type' => 'imgFrame',
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
        return '<img ' . trim($attrs) . '>';
    }
}