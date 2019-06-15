<?php

namespace Linlak\Jwt\Providers;

use Illuminate\Support\ServiceProvider;
use Linlak\Jwt\Console\Commands\CleanTokens;
use Linlak\Jwt\Console\Commands\InitJwt;
use Illuminate\Support\Facades\Auth;
use Linlak\Jwt\Auth\Guards\JwtGuard;
use Linlak\Jwt\Http\Middleware\Authanticate;
use Linlak\Jwt\Http\Middleware\Check;
use Linlak\Jwt\Http\Middleware\RefreshToken;

class JwtServiceProvider extends ServiceProvider
{
    protected $middlewareAliases = [
        'linjwt.auth' => Authanticate::class,
        'linjwt.check' => Check::class,
        'linjwt.refresh' => RefreshToken::class
    ];
    public function boot()
    {
        $this->loadLinMigrations();
        $this->registerCommands();
        $this->publishes([
            __DIR__ . '../../config/linjwt.php' => config_path('linjwt.php'),
        ]);
        Auth::extend('linjwt', function ($app, $name, array $config) {
            return new JwtGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
    }
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '../../config/linjwt.php',
            'linjwt'
        );
    }
    protected function loadLinMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '../../database/migrations');
    }
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanTokens::class,
                InitJwt::class,
            ]);
        }
    }
}
