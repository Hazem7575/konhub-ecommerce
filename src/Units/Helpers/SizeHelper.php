<?php

namespace Konhub\Lido\Units\Helpers;

use Illuminate\Support\Facades\File;

class SizeHelper
{
    public static function getWidth($width, $sizes = [])
    {
        if ($width == 0) return 0;

        $file = File::get(public_path('width.json'));
        $json = json_decode($file, true);
        $width_origin = $json['width'];
        if (count($sizes) > 0) {
            $width_origin = $sizes['width'];
        }
        return self::convertToPercentage($width, $width_origin);
    }

    public static function getHeight($height)
    {
        if ($height == 0) return 0;

        $file = File::get(public_path('width.json'));
        $json = json_decode($file, true);
        return self::convertToPercentage($height, $json['height']);
    }

    public static function convertToPercentage($pxValue, $rootValue)
    {
        return ($pxValue / $rootValue) * 100;
    }

    public static function calculateScale($imageWidth)
    {
        $file = File::get(public_path('width.json'));
        $json = json_decode($file, true);

        return $json['width'] / $imageWidth;
    }
}