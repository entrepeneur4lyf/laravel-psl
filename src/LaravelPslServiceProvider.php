<?php

declare(strict_types=1);

namespace LaravelPsl;

use Illuminate\Support\ServiceProvider;

final class LaravelPslServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/psl.php', 'psl');
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/psl.php' => config_path('psl.php'),
        ], 'laravel-psl-config');
    }
}
