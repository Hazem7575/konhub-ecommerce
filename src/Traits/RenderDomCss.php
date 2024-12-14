<?php

namespace Konhub\Lido\Traits;

use Konhub\Lido\Services\BaseStylesGenerator;
use Konhub\Lido\Services\CssMediaQueryService;
use Konhub\Lido\Services\CssStateManager;
use Konhub\Lido\Services\CssStyleProcessor;

trait RenderDomCss
{
    protected static function css_name($style, $prefix = null, $name = null, $is_class = true)
    {
        return CssStateManager::getInstance()->addStyle($style, $prefix, $name, $is_class);
    }

    protected static function FirstRenderCss()
    {
        $cssManager = CssStateManager::getInstance();
        $cssManager->setRenderCssCollection(
            BaseStylesGenerator::generate($cssManager->getLayerSize()['width'])
        );
    }

    protected static function RenderCollectionCss()
    {
        $cssManager = CssStateManager::getInstance();
        self::FirstRenderCss();
        self::RenderCssMediaQuery();
        $cssManager->appendTemplateCss($cssManager->getRenderCssCollection());
    }

    protected static function RenderCssMediaQuery()
    {
        $cssManager = CssStateManager::getInstance();
        foreach (CssMediaQueryService::getMediaQueries() as $mediaQuery) {
            $mediaQueryString = CssMediaQueryService::buildMediaQueryString($mediaQuery);
            $cssManager->appendRenderCssCollection('@media ' . $mediaQueryString . ' {');
            $cssManager->appendRenderCssCollection(self::ResponsiveCss($mediaQuery));
            $cssManager->appendRenderCssCollection('}');
        }
    }

    protected static function ResponsiveCss($mediaQuery)
    {
        $styles = '';
        $cssManager = CssStateManager::getInstance();
        $collection = $cssManager->getCollectionCss();

        if (!empty($collection)) {
            foreach ($collection as $key => $collect) {
                $prefix = $collect['is_class'] ? '.' : '#';
                $processedStyle = CssStyleProcessor::processStyle(
                    $collect['style'],
                    $mediaQuery['scale'],
                    $mediaQuery['max-width'] ?? null,
                    $cssManager->getLayerSize()['width']
                );
                $styles .= $prefix . $key . '{' . $processedStyle . '}';
            }
        }
        return $styles;
    }
}
