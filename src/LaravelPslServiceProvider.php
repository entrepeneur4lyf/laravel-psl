<?php

declare(strict_types=1);

namespace LaravelPsl;

use Illuminate\Support\ServiceProvider;
use LaravelPsl\Macros\RegistersCollectionMacros;

final class LaravelPslServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/psl.php', 'psl');
    }

    public function boot(): void
    {
        if ((bool) config('psl.features.collection_macros', true)) {
            RegistersCollectionMacros::register();
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/psl.php' => config_path('psl.php'),
            ], 'laravel-psl-config');
        }
    }
}
