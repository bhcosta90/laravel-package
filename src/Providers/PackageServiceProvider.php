<?php

namespace BRCas\Laravel\Providers;

use BRCas\Laravel\View\Components\Card\{CardBodyComponent, CardComponent, CardFilterComponent, CardHeaderComponent};
use BRCas\Laravel\View\Components\Table\{TableComponent};
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder as BuilderEloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();

        Builder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'" . $binding . "'", $sql, 1);
            }, $this->toSql());
        });

        BuilderEloquent::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });

        Str::macro('number', function ($str) {
            return preg_replace("/[^0-9]/", "", $str);
        });
    }

    public function boot()
    {
        $this->registerViews();
        $this->registerConfig();
        Blade::component('card', CardComponent::class);
        Blade::component('card-header', CardHeaderComponent::class);
        Blade::component('card-body', CardBodyComponent::class);
        Blade::component('card-filter', CardFilterComponent::class);
        Blade::component('table-list', TableComponent::class);
    }

    public function registerViews()
    {
        $viewPath = resource_path('views/modules/package');

        $sourcePath = __DIR__ . '/../Resources';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'package']);

        $this->loadViewsFrom($sourcePath, "bhcosta90-package");
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
            'bhcosta90-package'
        );
    }
}
