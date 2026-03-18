<?php

declare(strict_types=1);

namespace LaravelPsl\Macros;

use Illuminate\Support\Collection;

final class RegistersCollectionMacros
{
    public static function register(): void
    {
        Collection::mixin(new CollectionMacros, false);
    }
}
