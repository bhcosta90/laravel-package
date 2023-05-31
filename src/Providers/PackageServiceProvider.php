<?php

namespace BRCas\Laravel\Providers;

use BRCas\Laravel\View\Components\Card\{CardBodyComponent, CardComponent, CardFilterComponent, CardHeaderComponent};
use BRCas\Laravel\View\Components\Table\{TableComponent};
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder as BuilderEloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Collective\Html\FormBuilder as Form;

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

        Str::macro('is_active', function (string $title, string $url, null|bool $active) {
            $btn = "text-success";
            $icon = "fas fa-check-square";
            $title = __('Desabilitar ' . $title);

            if (empty($active)) {
                $btn = "text-danger";
                $icon = "far fa-square";
                $title = __('Habilitar' . $title);
            }

            return "<a href='" . $url . "'
                title='{$title}'
                data-btn-disable='text-danger'
                data-icon-disable='far fa-square'
                data-title-disable='" . __('Habilitar ' . $title) . "'
                data-btn-enable='text-success'
                data-icon-enable='fas fa-check-square'
                data-title-enable='" . __('Desabilitar ' . $title) . "'
                class='action-enable-disabled {$btn}'>
                <i class='{$icon}'></i>
            </a>";
        });

        Str::macro('numberFormat', function ($value, $decimal = 2) {
            switch (config('app.locale')) {
                case 'pt_BR':
                case 'pt-BR':
                case 'pt-br':
                    return "R$&nbsp;".number_format($value, $decimal, ',', '.');
                default:
                    return "US$&nbsp;" . number_format($value, $decimal);
            }
        });

        Str::macro('dayWeek', function ($value) {
            switch (config('app.locale')) {
                case 'pt_BR':
                case 'pt-BR':
                case 'pt-br':
                    $dayWeek = [
                        'Domingo',
                        'Segunda feira',
                        'Terça feira',
                        'Quarta feira',
                        'Quinta feira',
                        'Sexta feira',
                        'Sábado'
                    ];
                    break;
                default:
                    $dayWeek = [
                        'Sunday',
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday'
                    ];
            }

            return $dayWeek[$value];
        });

        Str::macro('formDelete', function($action, $id){
            $html = app(Form::class)->open([
                'url' => $action,
                'id' => "frm-" . $id,
                'method' => 'DELETE',
                'style' => 'display:none;',
                'class' => 'form-delete-confirmation'
            ]);
            $html .= "<button>{$action}</button>";
            $html .=  app(Form::class)->close();
            return $html;
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
