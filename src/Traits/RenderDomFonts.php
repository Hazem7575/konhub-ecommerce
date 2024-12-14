<?php

namespace Konhub\Lido\Traits;

use Illuminate\Support\Facades\File;
use Konhub\Lido\Services\CssStateManager;

trait RenderDomFonts
{
    protected static $fonts = [];
    protected static $fonts_css = '';

    public function getFonts()
    {
        if (isset(self::$template)) {
            foreach (self::$template as $layer) {
                if (isset($layer['props']['fonts'])) {
                    foreach ($layer['props']['fonts'] as $font) {
                        $fontName = $font['name'];
                        if (!isset(self::$fonts[$fontName])) {
                            self::$fonts[$fontName] = [];
                        }
                        foreach ($font['fonts'] as $fontVariant) {
                            if (isset($fontVariant['urls'])) {
                                foreach ($fontVariant['urls'] as $url) {
                                    self::$fonts[$fontName][] = [
                                        'url' => $url,
                                        'style' => $fontVariant['style'] ?? 'normal'
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    public function getFontsUrl()
    {
        $path_css = self::$paths_dir['path_font'];
        if (!File::exists(public_path($path_css))) {
            File::makeDirectory(public_path($path_css), 0755, true);
        }

        foreach (self::$fonts as $fontName => $fontVariants) {
            foreach ($fontVariants as $index => &$variant) {
                $originalUrl = $variant['url'];
                $fontFileName = self::generateFontFileName($fontName, $variant['style']);
                $localFontPath = $path_css . '/' . $fontFileName;

                if (!File::exists(public_path($localFontPath))) {
                    $fontData = file_get_contents($originalUrl);
                    File::put(public_path($localFontPath), $fontData);
                }

                self::$fonts[$fontName][$index]['url'] = $path_css . '/' . $fontFileName;
            }
        }

        return $this;
    }

    public function getRenderFonts()
    {
        foreach (self::$fonts as $fontName => $fontVariants) {
            foreach ($fontVariants as $variant) {
                self::$fonts_css .= '@font-face {';
                self::$fonts_css .= 'font-family: "' . $fontName . '"; ';
                self::$fonts_css .= 'src: url("' . $variant['url'] . '"); ';

                if (isset($variant['style'])) {
                    if (strpos($variant['style'], 'Italic') !== false) {
                        self::$fonts_css .= 'font-style: italic; ';
                    } else {
                        self::$fonts_css .= 'font-style: normal; ';
                    }

                    if (strpos($variant['style'], 'Bold') !== false) {
                        self::$fonts_css .= 'font-weight: bold; ';
                    } else {
                        self::$fonts_css .= 'font-weight: normal; ';
                    }
                } else {
                    self::$fonts_css .= 'font-style: normal; ';
                    self::$fonts_css .= 'font-weight: normal; ';
                }

                self::$fonts_css .= '}';
            }
        }

        return $this;
    }

    public function render()
    {
        CssStateManager::getInstance()->appendTemplateCss(self::$fonts_css);
        return self::$fonts_css;
    }

    protected static function generateFontFileName($fontName, $style)
    {
        $fileName = str_replace(' ', '-', strtolower($fontName));
        $stylePart = str_replace(' ', '-', strtolower($style));
        return $fileName . '-' . $stylePart . '.ttf';
    }
}
