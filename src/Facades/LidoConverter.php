<?php

namespace Konhub\Lido\Facades;

use Illuminate\Support\Facades\Facade;

class LidoConverter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lido.converter';
    }
}