<?php

namespace Konhub\Lido\Managers;

use Konhub\Lido\Services\ElementOrderManager;

class ResponsiveManager
{
    public static function generateResponsiveStyles(array $elements): string
    {
        $processedElements = NestedElementsManager::processNestedElements($elements);
        $sortedElements = ElementOrderManager::sortElementsByPosition($processedElements);
        $orderStyles = self::generateNestedOrderStyles($processedElements);

        return "
            @media (max-width: 768px) {
                .layer-container {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 1.5rem !important;
                    padding: 1rem;
                }

                [class*='store_'] {
                    width: 100% !important;
                    position: relative !important;
                    left: auto !important;
                    top: auto !important;
                    transform: none !important;
                    margin-bottom: 1rem;
                }

                /* حفظ التداخل في العناصر */
                .nested-container {
                    position: relative !important;
                    padding: 1rem !important;
                }

                .nested-element {
                    position: relative !important;
                    width: 100% !important;
                    margin: 0.5rem 0 !important;
                }

                /* تطبيق الترتيب مع مراعاة التداخل */
                {$orderStyles}

                /* تعديل حجم الخط */
                [class*='store_'] {
                    font-size: clamp(14px, 4vw, var(--font-size)) !important;
                }
            }
        ";
    }

    private static function generateNestedOrderStyles(array $elements): string
    {
        $styles = '';
        $order = 0;

        foreach ($elements as $id => $element) {
            if (!isset($element['parent_id'])) {
                $styles .= self::generateElementStyle($id, $order++);
                $styles .= self::generateNestedElementsStyles($elements, $id, $order);
            }
        }

        return $styles;
    }

    private static function generateElementStyle(string $id, int $order): string
    {
        return "[data-element-id='{$id}'] {
            order: {$order};
            position: relative !important;
        }\n";
    }

    private static function generateNestedElementsStyles(array $elements, string $parentId, int &$order): string
    {
        $styles = '';

        foreach ($elements as $id => $element) {
            if (isset($element['parent_id']) && $element['parent_id'] === $parentId) {
                $styles .= "[data-element-id='{$id}'] {
                    order: {$order};
                    margin-left: 1rem !important;
                    position: relative !important;
                }\n";
                $order++;
            }
        }

        return $styles;
    }
}
