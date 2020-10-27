<?php

namespace BRCas\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {

    }
    
    public function boot()
    {
        $this->registerViews();
    }

    public function registerViews()
    {
        $viewPath = resource_path('views/modules/package');

        $sourcePath = __DIR__  . '/../Resources';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'package']);

        $this->loadViewsFrom($sourcePath, "package");
    }
}