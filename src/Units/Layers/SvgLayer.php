<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Helpers\SvgHelper;
use Konhub\Lido\Units\Styles\GridElementUnit;

class SvgLayer
{
    public static function rander($element)
    {
        $style = '';
        $data = [
            'children' => []
        ];

        $data['children'][] = SvgHelper::getAttr($element);
        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }
}