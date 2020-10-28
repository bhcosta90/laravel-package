<?php

namespace BRCas\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();
    }

    public function boot()
    {
        $this->registerViews();
        $this->registerConfig();
    }

    public function registerViews()
    {
        $viewPath = resource_path('views/modules/package');

        $sourcePath = __DIR__ . '/../Resources';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'package']);

        $this->loadViewsFrom($sourcePath, "package");
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                realpath(__DIR__ . '/../Config/config.php') => config_path('package.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'package'
        );
    }
}
