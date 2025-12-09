<?php

namespace Texhub\UiComponents;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class UiComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ui');

        // Register Blade components under the "ui" alias (usage: <x-ui::sample />)
        Blade::componentNamespace('Texhub\\UiComponents\\View\\Components', 'ui');
    }
}
