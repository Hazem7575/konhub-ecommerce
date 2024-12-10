<?php

namespace Konhub\Lido\Units\Helpers;

use Konhub\Lido\Services\Json2HtmlConverter;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\ColorUnit;
use Konhub\Lido\Units\Styles\OpacityUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class SvgHelper
{
    public static function getAttr($element)
    {
        $style = '';
        $style .= TransformUnit::rander($element);
        $style .= ColorUnit::rander($element, 'color');
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= OpacityUnit::rander($element);

        $fillColor = $element['colors'][0] ?? 'none';

        $class_name = Json2HtmlConverter::ImageStyleListen($style, $element);
        return [
            'type' => 'svg',
            'attr' => [
                'width' => $element['boxSize']['width'],
                'height' => $element['boxSize']['height'] * 2,
                'class' => $class_name['class'],
                'viewBox' => "0 0 {$element['boxSize']['width']} {$element['boxSize']['height']}",
                'transform' => "translate({$element['position']['x']}, {$element['position']['y']})"
            ],
            'fill' => $fillColor,
            'image' => $element['image'],
            'colors' => $element['colors']
        ];
    }

    public static function render($element)
    {
        if (filter_var($element['image'], FILTER_VALIDATE_URL)) {
            $svgElement = file_get_contents($element['image']);
        } else {
            $svgData = $element['image'];
            $svgElement = base64_decode(substr($svgData, strpos($svgData, ",") + 1));
        }

        preg_match('/<svg[^>]*\s+width="(\d+\.?\d*)"/', $svgElement, $matches);
        preg_match('/<svg[^>]*\s+height="(\d+\.?\d*)"/', $svgElement, $matches2);

        if (isset($matches[1]) && isset($matches2[1])) {
            $element['attr']['viewBox'] = '0 0 ' . $matches[1] . ' ' . $matches2[1];
        }

        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }

        $svgElementWithAttributes = preg_replace('/<svg([^>]*)>/', '<svg$1 ' . trim($attrs) . ' preserveAspectRatio="none">', $svgElement);

        $gradientId = 'grad-' . uniqid();
        $linearGradient = '';
        if (count($element['colors']) > 1) {
            $linearGradient = "
            <defs>
                <linearGradient id='{$gradientId}' x1='0%' y1='0%' x2='100%' y2='100%'>
                    <stop offset='0%' style='stop-color:{$element['colors'][1]};stop-opacity:1' />
                    <stop offset='100%' style='stop-color:{$element['colors'][0]};stop-opacity:1' />
                </linearGradient>
            </defs>";
            $svgElementWithFillColor = preg_replace('/(<(path|rect|circle|ellipse|polygon|line|polyline)([^>]*?))/', '$1 fill="url(#' . $gradientId . ')"', $svgElementWithAttributes);
        } else {
            $svgElementWithFillColor = preg_replace('/(<(path|rect|circle|ellipse|polygon|line|polyline)([^>]*?))/', '$1 fill="' . htmlspecialchars($element['fill'], ENT_QUOTES) . '"', $svgElementWithAttributes);
        }

        $svgElementWithGradient = preg_replace('/(<svg[^>]*>)/', '$1' . $linearGradient, $svgElementWithFillColor);

        return $svgElementWithGradient;
    }
}