#!/usr/bin/env php
<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;
use LaravelPsl\LaravelPslServiceProvider;

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

if (! $app->providerIsLoaded(LaravelPslServiceProvider::class)) {
    fwrite(STDERR, "The LaravelPsl service provider was not auto-discovered.\n");

    exit(1);
}

if ($app['config']->get('psl.features.collection_macros') !== true) {
    fwrite(STDERR, "The discovered provider did not merge the default package config.\n");

    exit(1);
}

if (! Collection::hasMacro('pmap')) {
    fwrite(STDERR, "The discovered provider did not boot collection macros.\n");

    exit(1);
}

fwrite(STDOUT, "Package auto-discovery smoke test passed.\n");
