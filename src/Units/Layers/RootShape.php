<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Helpers\BackgroundHelper;
use Konhub\Lido\Units\Helpers\ImageHelper;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class RootShape
{
    public static function rander($element)
    {
        $data = [
            'children' => []
        ];

        $style = '';
        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element);
        $style .= 'display:grid;';

        if (isset($element['image']['url'])) {
            $data['children'][] = ImageHelper::getAttr($element['image'], $element, true);
        }

        if (isset($element['color'])) {
            $data['children'][] = BackgroundHelper::getAttr($element['color']);
        }

        $style .= 'overflow: hidden;position: relative;left:auto;right:auto;';
        $data['style'] = $style;

        return $data;
    }
}