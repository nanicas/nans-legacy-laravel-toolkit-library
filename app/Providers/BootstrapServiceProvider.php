<?php

namespace Nanicas\LegacyLaravelToolkit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Schema::defaultStringLength(191);

        $src = __DIR__ . '/../..';

        $this->publishes([
            $src . '/config' => config_path(),
        ], 'legacy_laravel_toolkit:config');
    }
}
