<?php

namespace Konhub\Lido\Units\Helpers;

use Konhub\Lido\Traits\RenderDomCss;
use Konhub\Lido\Traits\RenderDomJS;

class BackgroundHelper
{
    use RenderDomJS, RenderDomCss;

    public static function getAttr($color)
    {
        return [
            'type' => 'background',
            'attr' => [
                'style' => 'background:' . $color . ';width: 100%;height: 100%;',
            ]
        ];
    }

    public static function render($element)
    {
        $attrs = '';
        foreach ($element['attr'] as $key => $value) {
            $attrs .= $key . '="' . htmlspecialchars($value, ENT_QUOTES) . '" ';
        }

        return '<div style="grid-area:1 / 1 / 2 / 4;display:grid;position:absolute;min-height:100%;min-width:100%;">
            <div style="z-index:0;">
                <div style="box-sizing:border-box;width:100%;height:100%;transform:rotate(0deg);">
                    <div style="width:100%;height:100%;opacity:1.0;">
                        <div ' . trim($attrs) . '></div>
                    </div>
                </div>
            </div>
        </div>';
    }
}
