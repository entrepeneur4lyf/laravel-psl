<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Unit\Support;

use ArrayIterator;
use Illuminate\Support\Collection;
use LaravelPsl\Support\CollectionBridge;
use LaravelPsl\Support\DictBridge;
use LaravelPsl\Support\PslBridge;
use LaravelPsl\Tests\TestCase;

final class PslBridgeTest extends TestCase
{
    public function test_to_vec_reindexes_values_intentionally(): void
    {
        $collection = new Collection([
            'first' => 'alpha',
            10 => 'bravo',
            'third' => 'charlie',
        ]);

        self::assertSame(['alpha', 'bravo', 'charlie'], PslBridge::toVec($collection));
        self::assertSame(['alpha', 'bravo', 'charlie'], CollectionBridge::toVec($collection));
    }

    public function test_to_dict_preserves_mixed_runtime_keys(): void
    {
        $items = [
            'name' => 'Taylor',
            2 => 'Laravel',
            'role' => 'maintainer',
        ];

        self::assertSame($items, PslBridge::toDict($items));
        self::assertSame($items, DictBridge::toDict(new ArrayIterator($items)));
    }

    public function test_to_collection_preserves_runtime_keys(): void
    {
        $items = [
            'name' => 'Taylor',
            2 => 'Laravel',
            'role' => 'maintainer',
        ];

        $collection = PslBridge::toCollection($items);

        self::assertInstanceOf(Collection::class, $collection);
        self::assertSame($items, $collection->all());
    }

    public function test_to_collection_accepts_iterators(): void
    {
        $iterator = new ArrayIterator([
            'first' => 1,
            5 => 2,
            'third' => 3,
        ]);

        self::assertSame([
            'first' => 1,
            5 => 2,
            'third' => 3,
        ], CollectionBridge::toCollection($iterator)->all());
    }
}
