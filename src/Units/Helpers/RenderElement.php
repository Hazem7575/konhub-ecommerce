<?php

namespace Konhub\Lido\Units\Helpers;

class RenderElement
{
    public static function render($element)
    {
        return match ($element['type']) {
            'img' => ImageHelper::render($element),
            'imgFrame' => ImageFrameHelper::render($element),
            'text' => TextHelper::render($element),
            'svg' => SvgHelper::render($element),
            'background' => BackgroundHelper::render($element),
            default => '',
        };
    }
}
