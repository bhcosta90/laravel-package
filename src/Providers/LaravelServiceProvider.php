<?php

declare(strict_types=1);

namespace BRCas\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as BuilderEloquent;
use Illuminate\Database\Query\Builder;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        Builder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'" . $binding . "'", $sql, 1);
            }, $this->toSql());
        });

        BuilderEloquent::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });

        Str::macro('number', function ($str) {
            return $str ? preg_replace("/[^0-9]/", "", $str) : 0.00;
        });

        Str::macro('numberFormat', function ($value, $decimal = 2) {
            switch (config('app.locale')) {
                case 'pt_BR':
                case 'pt-BR':
                case 'pt-br':
                    return "R$&nbsp;" . number_format($value, $decimal, ',', '.');
                default:
                    return "US$&nbsp;" . number_format($value, $decimal);
            }
        });
    }
}
