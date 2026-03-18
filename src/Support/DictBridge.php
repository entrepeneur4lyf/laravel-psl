<?php

declare(strict_types=1);

namespace LaravelPsl\Support;

use function Psl\Dict\from_iterable;

final class DictBridge
{
    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  iterable<TKey, TValue>  $items
     * @return array<TKey, TValue>
     */
    public static function toDict(iterable $items): array
    {
        return from_iterable($items);
    }
}
