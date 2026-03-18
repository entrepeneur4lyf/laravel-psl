<?php

declare(strict_types=1);

namespace LaravelPsl\Macros;

use Illuminate\Support\Collection;

final class CollectionMacroContext
{
    /**
     * @return Collection<array-key, mixed>
     */
    public static function collection(object $context): Collection
    {
        if (! $context instanceof Collection) {
            throw new \LogicException('Collection macro was not bound to an Illuminate collection instance.');
        }

        return $context;
    }
}
