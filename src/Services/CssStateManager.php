<?php

namespace Konhub\Lido\Services;

class CssStateManager
{
    private static $instance = null;
    private $collectionCss = [];
    private $renderCssCollection = '';
    private $templateCss = '';
    private $layerSize = ['width' => 0, 'height' => 0];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setLayerSize(array $size): void
    {
        $this->layerSize = $size;
    }

    public function getLayerSize(): array
    {
        return $this->layerSize;
    }

    public function addStyle(string $style, ?string $prefix = null, ?string $name = null, bool $isClass = true): string
    {
        if ($name === null) {
            $name = 'store_' . str_replace(['+', '/', '='], '', base64_encode(random_bytes(8)));
        }

        $name = $prefix . $name;

        $this->collectionCss[$name] = [
            'is_class' => $isClass,
            'style' => $style,
        ];

        return $name;
    }

    public function getCollectionCss(): array
    {
        return $this->collectionCss;
    }

    public function setRenderCssCollection(string $css): void
    {
        $this->renderCssCollection = $css;
    }

    public function appendRenderCssCollection(string $css): void
    {
        $this->renderCssCollection .= $css;
    }

    public function getRenderCssCollection(): string
    {
        return $this->renderCssCollection;
    }

    public function setTemplateCss(string $css): void
    {
        $this->templateCss = $css;
    }

    public function appendTemplateCss(string $css): void
    {
        $this->templateCss .= $css;
    }

    public function getTemplateCss(): string
    {
        return $this->templateCss;
    }
}
