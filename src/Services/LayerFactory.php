<?php

namespace Konhub\Lido\Services;

use Konhub\Lido\Units\Layers\{
    FrameLayer,
    GroupLayer,
    ImageLayer,
    LineLayer,
    RootShape,
    ShapeLayer,
    SvgLayer,
    TextLayer
};
use Konhub\Lido\Exceptions\LidoException;

class LayerFactory
{
    private const LAYER_MAP = [
        'RootLayer' => RootShape::class,
        'ShapeLayer' => ShapeLayer::class,
        'FrameLayer' => FrameLayer::class,
        'TextLayer' => TextLayer::class,
        'GroupLayer' => GroupLayer::class,
        'SvgLayer' => SvgLayer::class,
        'ImageLayer' => ImageLayer::class,
        'LineLayer' => LineLayer::class,
    ];

    public static function create(string $type, array $props)
    {
        if (!isset(self::LAYER_MAP[$type])) {
            throw LidoException::invalidLayerType($type);
        }

        $layerClass = self::LAYER_MAP[$type];
        return $layerClass::rander($props);
    }
}
