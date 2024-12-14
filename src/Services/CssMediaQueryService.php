<?php

namespace Konhub\Lido\Services;

class CssMediaQueryService
{
    private static $mediaQueries = [
        [
            'max-width' => '375px',
            'scale' => 0.3,
        ],
        [
            'min-width' => '375.05px',
            'max-width' => '480px',
            'scale' => 0.4,
        ],
        [
            'min-width' => '480.05px',
            'max-width' => '768px',
            'scale' => 0.6,
        ],
        [
            'min-width' => '768.05px',
            'max-width' => '1024px',
            'scale' => 0.8,
        ],
        [
            'min-width' => '1024.05px',
            'scale' => 1,
        ]
    ];

    public static function getMediaQueries(): array
    {
        return self::$mediaQueries;
    }

    public static function buildMediaQueryString(array $query): string
    {
        $conditions = [];

        if (isset($query['min-width'])) {
            $conditions[] = "(min-width: {$query['min-width']})";
        }
        if (isset($query['max-width'])) {
            $conditions[] = "(max-width: {$query['max-width']})";
        }

        return implode(' and ', $conditions);
    }
}
