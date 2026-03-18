<?php

declare(strict_types=1);

namespace LaravelPsl\Support;

use Illuminate\Support\Collection;

final class PslBridge
{
    /**
     * @template TValue
     *
     * @param  iterable<array-key, TValue>  $items
     * @return list<TValue>
     */
    public static function toVec(iterable $items): array
    {
        return CollectionBridge::toVec($items);
    }

    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  iterable<TKey, TValue>  $items
     * @return array<TKey, TValue>
     */
    public static function toDict(iterable $items): array
    {
        return DictBridge::toDict($items);
    }

    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  iterable<TKey, TValue>  $items
     * @return Collection<TKey, TValue>
     */
    public static function toCollection(iterable $items): Collection
    {
        return CollectionBridge::toCollection($items);
    }
}
