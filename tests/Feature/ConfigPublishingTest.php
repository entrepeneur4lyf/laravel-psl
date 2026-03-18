<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use LaravelPsl\LaravelPslServiceProvider;
use LaravelPsl\Tests\TestCase;
use ReflectionClass;

final class ConfigPublishingTest extends TestCase
{
    public function test_it_registers_publishable_config_paths(): void
    {
        $provider = new LaravelPslServiceProvider($this->app);
        $provider->boot();

        $paths = ServiceProvider::pathsToPublish(LaravelPslServiceProvider::class, 'laravel-psl-config');
        $providerDirectory = dirname((new ReflectionClass(LaravelPslServiceProvider::class))->getFileName());
        $sourcePath = $providerDirectory.'/../config/psl.php';

        self::assertCount(1, $paths);
        self::assertArrayHasKey($sourcePath, $paths);
        self::assertSame(config_path('psl.php'), $paths[$sourcePath]);
    }
}
