<?php


namespace Konhub\Lido\Services;

class ElementOrderManager
{
    public static function sortElementsByPosition(array $elements): array
    {
        usort($elements, function($a, $b) {
            $aTop = self::getElementTop($a);
            $bTop = self::getElementTop($b);
            return $aTop <=> $bTop;
        });

        return $elements;
    }

    private static function getElementTop($element): int
    {
        return $element['props']['position']['y'] ?? PHP_INT_MAX;
    }
}
