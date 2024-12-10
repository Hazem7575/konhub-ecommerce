<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\ColorUnit;
use Konhub\Lido\Units\Styles\GridElementUnit;
use Konhub\Lido\Units\Styles\OpacityUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class LineLayer
{
    public static function rander($element)
    {
        $style = '';
        $data = [
            'children' => []
        ];

        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);
        $style .= ColorUnit::rander($element, 'background');
        $style .= OpacityUnit::rander($element);

        if (isset($element['strokeWidth'])) {
            $style .= 'stroke-width: ' . $element['strokeWidth'] . 'px; ';
        }

        if (isset($element['style'])) {
            $style .= 'stroke-dasharray: ' . ($element['style'] === 'dashed' ? '5, 5' : '0') . '; ';
        }

        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }
}