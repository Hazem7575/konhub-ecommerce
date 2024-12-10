<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\GridElementUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class GroupLayer
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

        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }
}