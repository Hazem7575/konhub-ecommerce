<?php

namespace Konhub\Lido\Helpers;

class ElementHelper
{
    public static function addDataAttributes(array $element): array
    {
        $element['attributes'] = $element['attributes'] ?? [];
        $element['attributes']['data-element-id'] = $element['id'];

        if (isset($element['parent_id'])) {
            $element['attributes']['data-parent-id'] = $element['parent_id'];
            $element['attributes']['class'] = ($element['attributes']['class'] ?? '') . ' nested-element';
        }

        if (isset($element['props']['position']['y'])) {
            $element['attributes']['data-position-y'] = $element['props']['position']['y'];
        }

        return $element;
    }

    public static function generateElementHtml(array $element): string
    {
        $attributes = self::buildAttributesString($element['attributes']);
        $classes = self::getElementClasses($element);

        return "<div {$attributes} class='{$classes}'>{$element['content']}</div>";
    }

    private static function getElementClasses(array $element): string
    {
        $classes = ['layer-element'];

        if (isset($element['parent_id'])) {
            $classes[] = 'nested-element';
        } else {
            $classes[] = 'parent-element';
        }

        if (self::hasNestedElements($element)) {
            $classes[] = 'nested-container';
        }

        return implode(' ', $classes);
    }

    private static function hasNestedElements(array $element): bool
    {
        return isset($element['child']) && !empty($element['child']);
    }

    private static function buildAttributesString(array $attributes): string
    {
        return implode(' ', array_map(
            fn($key, $value) => "{$key}=\"{$value}\"",
            array_keys($attributes),
            $attributes
        ));
    }
}
