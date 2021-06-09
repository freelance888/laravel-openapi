<?php

namespace AllatraIt\LaravelOpenapi;

use Illuminate\Support\ServiceProvider;

class LaravelOpenapiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishables();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'openapi');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/openapi.php', 'openapi');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/openapi.php' => config_path('openapi.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/openapi'),
        ], 'views');
    }
}
