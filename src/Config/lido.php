<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asset Paths
    |--------------------------------------------------------------------------
    |
    | Configure the paths where Lido will store generated assets
    |
    */
    'paths' => [
        'css' => '/test/css/',
        'js' => '/test/js/',
        'fonts' => '/test/fonts',
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Queries
    |--------------------------------------------------------------------------
    |
    | Configure the breakpoints for responsive design
    |
    */
    'media_queries' => [
        [
            'max-width' => '375px',
        ],
        [
            'min-width' => '375.05px',
            'max-width' => '480px',
        ],
        [
            'min-width' => '480.05px',
            'max-width' => '768px',
        ],
        [
            'min-width' => '768.05px',
            'max-width' => '1024px',
        ],
        [
            'min-width' => '1024.05px',
        ]
    ],
];
