<?php
namespace Linlak\Jwt\Facades;

use Illuminate\Support\Facades\Facade;

class LinJwt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'linjwt';
    }
    /**
     * Resolve the facade root instance from the container.
     *
     * @param  string  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (!isset(static::$resolvedInstance[$name]) && !isset(static::$app, static::$app[$name])) {
            $class = static::DEFAULT_FACADE;

            static::swap(new $class);
        }

        return parent::resolveFacadeInstance($name);
    }
}
