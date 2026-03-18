<?php

declare(strict_types=1);

namespace LaravelPsl\Support;

use Illuminate\Support\Collection;

use function Psl\Vec\values;

final class CollectionBridge
{
    /**
     * @template TValue
     *
     * @param  iterable<array-key, TValue>  $items
     * @return list<TValue>
     */
    public static function toVec(iterable $items): array
    {
        return values($items);
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
        return new Collection(DictBridge::toDict($items));
    }
}
