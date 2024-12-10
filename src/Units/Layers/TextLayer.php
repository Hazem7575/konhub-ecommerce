<?php

namespace Konhub\Lido\Units\Layers;

use Konhub\Lido\Units\Effects\EchoEffect;
use Konhub\Lido\Units\Effects\HollowEffect;
use Konhub\Lido\Units\Effects\LiftEffect;
use Konhub\Lido\Units\Effects\ShadowEffect;
use Konhub\Lido\Units\Effects\SpliceEffect;
use Konhub\Lido\Units\Helpers\TextHelper;
use Konhub\Lido\Units\Styles\BoxSizeUnit;
use Konhub\Lido\Units\Styles\FontSizeUnit;
use Konhub\Lido\Units\Styles\GridElementUnit;
use Konhub\Lido\Units\Styles\PositionUnit;
use Konhub\Lido\Units\Styles\TransformUnit;

class TextLayer
{
    public static function rander($element)
    {
        $style = '';
        $data = [
            'children' => []
        ];

        if (array_key_exists('position', $element) && array_key_exists('boxSize', $element)) {
            if (array_search('position', array_keys($element)) < array_search('boxSize', array_keys($element))) {
                $style .= PositionUnit::rander($element['position']);
                $style .= BoxSizeUnit::rander($element['boxSize'], false);
            } else {
                $style .= BoxSizeUnit::rander($element['boxSize'], false);
                $style .= PositionUnit::rander($element['position']);
            }
        }

        $style .= FontSizeUnit::rander($element);
        $style .= TransformUnit::rander($element);

        if (isset($element['text'])) {
            $data['children'][] = TextHelper::getAttr($element);
        }

        if (isset($element['fonts'])) {
            foreach ($element['fonts'] as $font) {
                if (isset($font['name'], $font['fonts'][0]['urls'][0])) {
                    $style .= 'font-family: ' . $font['name'] . '; ';
                }
            }
        }

        if (isset($element['effect'])) {
            $style .= self::matchTextEffect($element);
        }

        $data['style'] = $style;
        $data['grid'] = GridElementUnit::rander($element);

        return $data;
    }

    private static function matchTextEffect(array $element): string
    {
        return match ($element['effect']['name']) {
            'hollow' => HollowEffect::render($element),
            'lift' => LiftEffect::render($element),
            'shadow' => ShadowEffect::render($element),
            'echo' => EchoEffect::render($element),
            'splice' => SpliceEffect::render($element),
            default => '',
        };
    }
}