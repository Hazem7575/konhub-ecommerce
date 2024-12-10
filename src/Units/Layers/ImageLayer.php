<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Helpers\ImageHelper;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\ClipPathUnit;
use Konhub\Lido\Units\Styles\ColorUnit;
use Konhub\Lido\Units\Styles\GridElementUnit;
use Konhub\Lido\Units\Styles\OpacityUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class ImageLayer
{
    public static function rander($element, $is_root = false)
    {
        $style = '';
        $data = [
            'children' => []
        ];

        $style .= BoxSizeUnit::rander($element['boxSize']);
        $style .= PositionUnit::rander($element['position']);
        $style .= TransformUnit::rander($element, true);
        $style .= ClipPathUnit::rander($element);
        $style .= ColorUnit::rander($element);
        $style .= OpacityUnit::rander($element);
        $style .= 'overflow: hidden;';

        if (isset($element['image']['flipHorizontal']) && $element['image']['flipHorizontal']) {
            $style .= 'transform: scale(-1, 1);';
        }

        $data['children'][] = ImageHelper::getAttr($element['image'], $element, $is_root);
        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }
}