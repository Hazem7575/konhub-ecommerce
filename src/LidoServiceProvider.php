<?php

namespace Konhub\Lido;

use Illuminate\Support\ServiceProvider;
use Konhub\Lido\Services\Json2HtmlConverter;

class LidoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('lido.converter', function ($app) {
            return new Json2HtmlConverter();
        });
    }

    public function boot()
    {
        // Boot logic here
    }
}