<?php

namespace Konhub\Lido\Managers;

class NestedElementsManager
{
    public static function processNestedElements(array $elements): array
    {
        $processed = [];
        foreach ($elements as $id => $element) {
            $element['parent_id'] = self::findParentElement($element, $elements);
            $processed[$id] = $element;
        }
        return $processed;
    }

    private static function findParentElement($element, $allElements): ?string
    {
        if (!isset($element['props']['position'])) {
            return null;
        }

        $elementX = $element['props']['position']['x'];
        $elementY = $element['props']['position']['y'];
        $elementWidth = $element['props']['boxSize']['width'];
        $elementHeight = $element['props']['boxSize']['height'];

        foreach ($allElements as $id => $potentialParent) {
            if ($element === $potentialParent) continue;

            if (self::isElementInside($elementX, $elementY, $elementWidth, $elementHeight, $potentialParent)) {
                return $id;
            }
        }

        return null;
    }

    private static function isElementInside($x, $y, $width, $height, $parent): bool
    {
        if (!isset($parent['props']['position']) || !isset($parent['props']['boxSize'])) {
            return false;
        }

        $parentX = $parent['props']['position']['x'];
        $parentY = $parent['props']['position']['y'];
        $parentWidth = $parent['props']['boxSize']['width'];
        $parentHeight = $parent['props']['boxSize']['height'];

        return $x >= $parentX &&
            $y >= $parentY &&
            ($x + $width) <= ($parentX + $parentWidth) &&
            ($y + $height) <= ($parentY + $parentHeight);
    }
}
