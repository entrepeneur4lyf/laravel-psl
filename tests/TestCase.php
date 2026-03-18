<?php

declare(strict_types=1);

namespace LaravelPsl\Tests;

use LaravelPsl\LaravelPslServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelPslServiceProvider::class,
        ];
    }
}
