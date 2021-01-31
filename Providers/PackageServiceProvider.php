<?php

namespace Costa\Package\Providers;

use Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


class PackageServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Package';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'costa_package';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Database\Query\Builder::macro('toRawSql', function(){
            return array_reduce($this->getBindings(), function($sql, $binding){
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'" , $sql, 1);
            }, $this->toSql());
        });

        \Illuminate\Database\Eloquent\Builder::macro('toRawSql', function(){
            return ($this->getQuery()->toRawSql());
        });

        $this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . "/../Config/config.php" => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . "/../Config/config.php", $this->moduleNameLower
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Faker\Generator::class, function () {
            return \Faker\Factory::create('pt_BR');
        });
        
        Str::macro('price', function (?string $str) {
            return number_format($str, 2, ',', '.');
        });

        Str::macro('nullable', function (?string $str) {
            return !empty($str) ? $str : null;
        });

        Str::macro('implode', function (string $delimiter, ?string $str) {
            return !empty($str) ? "{$delimiter}{$str}" : "";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
