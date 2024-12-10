<?php

namespace Konhub\Lido\Traits;

trait RenderDomJS
{
    protected static function put_size($style, $class_name, $child = [])
    {
        preg_match('/width:\s*([0-9\.]+)px;/', $style, $width_matches);
        preg_match('/height:\s*([0-9\.]+)px;/', $style, $height_matches);
        preg_match('/grid-template-columns:\s*([^;]+);/', $style, $matches);

        $width = isset($width_matches[1]) ? $width_matches[1] : null;
        $height = isset($height_matches[1]) ? $height_matches[1] : null;
        $grid = isset($matches[1]) ? $matches[1] : null;

        $data = [];

        if (isset($width)) {
            $data['width'] = $width;
        }
        if (isset($height)) {
            $data['height'] = $height;
        }
        if (isset($grid)) {
            $data['grid'] = $grid;
        }
        if (isset($child['props']['position']['x'])) {
            $data['x'] = (string)$child['props']['position']['x'];
        }
        if (isset($child['props']['position']['y'])) {
            $data['y'] = (string)$child['props']['position']['y'];
        }
        if (isset($child['child'])) {
            $data['child'] = count($child['child']);
        }
        if (isset($child['type']['resolvedName']) && $child['type']['resolvedName'] == 'RootLayer') {
            $data['parent'] = true;
        }

        if (count($data) > 0) {
            self::$width_layers[$class_name] = $data;
        }
    }
}
