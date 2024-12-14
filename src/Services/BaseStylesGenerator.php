<?php

namespace Konhub\Lido\Services;

class BaseStylesGenerator
{
    public static function generate(int $layerWidth): string
    {
        return '
            @media (prefers-reduced-motion: reduce) {
                .animated {
                    animation: none !important;
                }
            }
            :root {
               --layer-size: ' . $layerWidth . 'px;
               --mobile-breakpoint: 768px;
               --container-padding: 1rem;
            }

            html {
                -webkit-text-size-adjust: 100%;
                scroll-behavior: smooth;
            }
            body,
            html,
            p,
            ul,
            ol,
            li {
                margin: 0;
                padding: 0;
                font-synthesis: none;
                font-kerning: none;
                font-variant-ligatures: none;
                font-feature-settings: "kern" 0, "calt" 0, "liga" 0, "clig" 0, "dlig" 0, "hlig" 0;
                font-family: unset;
                -webkit-font-smoothing: subpixel-antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: geometricprecision;
                white-space: normal;
            }
            * {
                box-sizing: border-box;
            }
            img {

            }
            [class*="store_"] {
                transition: transform 0.3s ease, width 0.3s ease, height 0.3s ease;

            }
            .responsive-grid {
                display: grid;
                gap: 1rem;
                width: 100%;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
            @media (max-width: 768px) {
                .layer-contianer {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 1rem !important;
                }
                [class*="store_"] {
                    position: relative !important;
                    left: auto !important;
                    top: auto !important;
                    width: 100% !important;
                    transform: none !important;
                    margin-bottom: 1rem !important;
                }
                div[style*="grid-area"] {
                    grid-area: unset !important;
                }
                div[style*="position: absolute"] {
                    position: relative !important;
                    transform: none !important;
                }
            }
        ';
    }
}
