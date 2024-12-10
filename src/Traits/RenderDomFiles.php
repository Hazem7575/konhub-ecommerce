<?php

namespace Konhub\Lido\Traits;

use Konhub\Lido\Services\CssFormatter;
use Illuminate\Support\Facades\File;

trait RenderDomFiles
{
    protected static $file_name_css = 'styles.css';
    protected static $file_name_js = 'main.js';

    protected static function renderFileCss()
    {
        $path_css = self::$paths_dir['path_css'];
        if (!File::exists(public_path($path_css))) {
            File::makeDirectory(public_path($path_css), 0755, true);
        }

        self::RenderCollectionCss();
        self::render_javascript();

        $formatter = new CssFormatter();
        $cssFilePath = $path_css . self::$file_name_css;
        $formattedCss = $formatter->format(self::$template_css);
        File::put(public_path($cssFilePath), $formattedCss);

        return new static();
    }

    protected static function getUrlCss()
    {
        return asset(self::$paths_dir['path_css'] . self::$file_name_css);
    }

    protected static function getUrlJs()
    {
        return asset(self::$paths_dir['path_js'] . self::$file_name_js);
    }

    protected static function render_javascript()
    {
        $path_js = self::$paths_dir['path_js'];
        if (!File::exists(public_path($path_js))) {
            File::makeDirectory(public_path($path_js), 0755, true);
        }

        $width_layers_json = json_encode(self::$width_layers, JSON_PRETTY_PRINT);
        $js_file_path = self::$file_name_js;
        $javascript_content = "var sizesElements = {$width_layers_json};";
        $cssFilePath = $path_js . $js_file_path;

        File::put(public_path($cssFilePath), $javascript_content);
    }
}
