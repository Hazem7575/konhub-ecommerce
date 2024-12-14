<?php
// src/Services/MobileStyleManager.php

namespace Konhub\Lido\Services;

class MobileStyleManager
{
    public static function generateMobileStyles(array $elements): string
    {
        $sortedElements = ElementOrderManager::sortElementsByPosition($elements);
        $styles = [];

        foreach ($sortedElements as $index => $element) {
            $elementId = $element['id'];
            $order = $index + 1;

            $styles[] = "[data-element-id=\"{$elementId}\"] {
                order: {$order};
                width: 100% !important;
                position: relative !important;
                left: auto !important;
                top: auto !important;
                transform: none !important;
                margin-bottom: 1rem;
            }";
        }

        return implode("\n", $styles);
    }
}
