<?php

namespace Konhub\Lido\Units\Styles;

class GridUnit
{
    protected static $childColumns = [];
    protected static $childRows = [];

    public static function rander($element, &$collection)
    {
        static::$childColumns = [];
        static::$childRows = [];
        $style = '';

        if (!empty($element['child'])) {
            foreach ($element['child'] as $childName) {
                $childElement = $collection[$childName];
                self::processChildElement($childElement, $element, $childName);
            }

            $style .= self::buildGridColumn();
            $style .= self::buildGridRow();
            $style .= "display: grid;";

            self::applyGridPositionsToChildren($collection, static::$childColumns, 'column');
            self::applyGridPositionsToChildren($collection, static::$childRows, 'row');

            // Add responsive styles
            $style .= self::getResponsiveStyles();
        }

        return $style;
    }

    private static function processChildElement($childElement, $parentElement, $childName)
    {
        static::$childColumns[] = [
            'x' => min($childElement['props']['position']['x'], $parentElement['props']['boxSize']['width']),
            'id' => $childName,
            'type' => 'column-start',
            'width' => $childElement['props']['boxSize']['width']
        ];
        static::$childColumns[] = [
            'x' => min($childElement['props']['position']['x'] + $childElement['props']['boxSize']['width'], $parentElement['props']['boxSize']['width']),
            'id' => $childName,
            'type' => 'column-end'
        ];
        static::$childRows[] = [
            'y' => min($childElement['props']['position']['y'], $parentElement['props']['boxSize']['height']),
            'id' => $childName,
            'type' => 'row-start'
        ];
        static::$childRows[] = [
            'y' => min($childElement['props']['position']['y'] + $childElement['props']['boxSize']['height'], $parentElement['props']['boxSize']['height']),
            'id' => $childName,
            'type' => 'row-end'
        ];
    }

    private static function buildGridColumn()
    {
        usort(static::$childColumns, fn($a, $b) => $a['x'] <=> $b['x']);
        self::adjustNegativeValues(static::$childColumns, 'x');

        $columns = [];
        $style = '';

        foreach (static::$childColumns as $key => $child) {
            $value = ($key === 0) ? $child['x'] : $child['x'] - static::$childColumns[$key - 1]['x'];
            $columns[] = $value;
            static::$childColumns[$key]['column'] = count($columns) + 1;
            $style .= $value / 16 . "rem ";
        }

        return 'grid-template-columns: ' . trim($style) . ';';
    }

    private static function buildGridRow()
    {
        usort(static::$childRows, fn($a, $b) => $a['y'] <=> $b['y']);
        self::adjustNegativeValues(static::$childRows, 'y');

        $rows = [];
        $style = '';

        foreach (static::$childRows as $key => $child) {
            $value = ($key === 0) ? $child['y'] : $child['y'] - static::$childRows[$key - 1]['y'];
            $rows[] = $value;
            static::$childRows[$key]['row'] = count($rows) + 1;

            if ($value) {
                $style .= 'minmax(' . ($value / 16) . "rem, max-content) ";
            } else {
                $style .= "0 ";
            }
        }

        return 'grid-template-rows: ' . trim($style) . ';';
    }

    private static function getResponsiveStyles()
    {
        return "
            @media (max-width: 768px) {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)) !important;
                grid-template-rows: auto !important;
                gap: 1rem;
                padding: 1rem;

                > * {
                    grid-column: auto !important;
                    grid-row: auto !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin-bottom: 1rem;
                    position: relative !important;
                    left: auto !important;
                    top: auto !important;
                    transform: none !important;
                }
            }

            @media (min-width: 769px) and (max-width: 1024px) {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem;

                > * {
                    width: 100% !important;
                    max-width: 100% !important;
                }
            }
        ";
    }

    private static function applyGridPositionsToChildren(&$collection, $children, $type)
    {
        foreach ($children as $child) {
            if (isset($collection[$child['id']])) {
                $collection[$child['id']]['props']['grid'][$child['type']] = $child[$type] ?? null;
            }
        }
    }

    private static function adjustNegativeValues(&$elements, $key)
    {
        foreach ($elements as $index => $value) {
            if ($value[$key] < 0) {
                $elements[$index][$key] = 0;
            }
        }
    }
}
