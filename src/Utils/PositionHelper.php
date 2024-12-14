<?php

// src/Utils/PositionHelper.php

namespace Konhub\Lido\Utils;

class PositionHelper
{
    public static function calculateElementOrder($element): int
    {
        if (!isset($element['props']['position']['y'])) {
            return PHP_INT_MAX;
        }

        return $element['props']['position']['y'];
    }

    public static function shouldReorder($element): bool
    {
        return isset($element['props']['position']) &&
            isset($element['props']['position']['y']);
    }
}
