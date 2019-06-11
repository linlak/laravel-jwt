<?php
namespace Linlak\Jwt\Facades;

use Illuminate\Support\Facades\Facade;

class LinJwt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'linjwt';
    }
}
