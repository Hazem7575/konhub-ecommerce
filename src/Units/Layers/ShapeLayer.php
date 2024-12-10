<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Helpers\ImageHelper;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\ColorUnit;
use Konhub\Lido\Units\Styles\GridElementUnit;
use Konhub\Lido\Units\Styles\OpacityUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\RoundedCornersUnit;
use Konhub\Lido\Units\Styles\ShapeUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class ShapeLayer
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
        $style .= ColorUnit::rander($element);
        $style .= OpacityUnit::rander($element);
        $style .= RoundedCornersUnit::rander($element);
        $style .= ShapeUnit::rander($element);

        if (isset($element['image']['url'])) {
            $data['children'][] = ImageHelper::getAttr($element['image'], $element);
        }

        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }
}