<?php

namespace Konhub\Lido\Services;

class CssFormatter
{
    protected $indentation = '  ';

    public function format($css)
    {
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/\s*{\s*/', " {\n", $css);
        $css = preg_replace('/;\s*/', ";\n" . $this->indentation, $css);
        $css = preg_replace('/\s*}\s*/', "\n}\n", $css);

        return $css;
    }

    public function minify($css)
    {
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/\s*({|}|;|:|,)\s*/', '$1', $css);
        return trim($css);
    }
}