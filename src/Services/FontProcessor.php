<?php

namespace Konhub\Lido\Services;

use Illuminate\Support\Facades\File;

class FontProcessor
{
    private $fonts = [];
    private $fontsPath;

    public function __construct(string $fontsPath)
    {
        $this->fontsPath = $fontsPath;
    }

    public function processFonts(array $layer): void
    {
        if (!isset($layer['props']['fonts'])) {
            return;
        }

        foreach ($layer['props']['fonts'] as $font) {
            $this->processFont($font);
        }
    }

    private function processFont(array $font): void
    {
        $fontName = $font['name'];
        if (!isset($this->fonts[$fontName])) {
            $this->fonts[$fontName] = [];
        }

        foreach ($font['fonts'] as $fontVariant) {
            if (!isset($fontVariant['urls'])) {
                continue;
            }

            foreach ($fontVariant['urls'] as $url) {
                $this->fonts[$fontName][] = [
                    'url' => $url,
                    'style' => $fontVariant['style'] ?? 'normal'
                ];
            }
        }
    }

    public function downloadFonts(): void
    {
        if (!File::exists(public_path($this->fontsPath))) {
            File::makeDirectory(public_path($this->fontsPath), 0755, true);
        }

        foreach ($this->fonts as $fontName => $variants) {
            foreach ($variants as &$variant) {
                $this->downloadFont($fontName, $variant);
            }
        }
    }

    private function downloadFont(string $fontName, array &$variant): void
    {
        $originalUrl = $variant['url'];
        $fontFileName = $this->generateFontFileName($fontName, $variant['style']);
        $localFontPath = $this->fontsPath . '/' . $fontFileName;

        if (!File::exists(public_path($localFontPath))) {
            $fontData = file_get_contents($originalUrl);
            File::put(public_path($localFontPath), $fontData);
        }

        $variant['url'] = $this->fontsPath . '/' . $fontFileName;
    }

    private function generateFontFileName(string $fontName, string $style): string
    {
        $fileName = str_replace(' ', '-', strtolower($fontName));
        $stylePart = str_replace(' ', '-', strtolower($style));
        return $fileName . '-' . $stylePart . '.ttf';
    }

    public function getFonts(): array
    {
        return $this->fonts;
    }
}
