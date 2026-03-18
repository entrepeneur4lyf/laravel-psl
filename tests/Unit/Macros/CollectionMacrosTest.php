<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Unit\Macros;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use LaravelPsl\Macros\RegistersCollectionMacros;
use LaravelPsl\Tests\TestCase;

final class CollectionMacrosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Collection::flushMacros();
        RegistersCollectionMacros::register();
    }

    protected function tearDown(): void
    {
        Collection::flushMacros();

        parent::tearDown();
    }

    public function test_pmap_and_pfilter_return_reindexed_collections(): void
    {
        $result = collect([
            'first' => 1,
            10 => 2,
            'third' => 3,
        ])
            ->pfilter(static fn (int $value): bool => $value !== 2)
            ->pmap(static fn (int $value): int => $value * 10);

        self::assertInstanceOf(Collection::class, $result);
        self::assertSame([10, 30], $result->all());
    }

    public function test_preduce_returns_the_final_accumulator(): void
    {
        $result = collect([
            'first' => 1,
            'second' => 2,
            'third' => 3,
        ])->preduce(
            static fn (int $carry, int $value): int => $carry + $value,
            10,
        );

        self::assertSame(16, $result);
    }

    public function test_pchunk_returns_a_collection_of_reindexed_collections(): void
    {
        $result = collect([
            'first' => 1,
            5 => 2,
            'third' => 3,
        ])->pchunk(2);

        self::assertInstanceOf(Collection::class, $result);
        self::assertInstanceOf(Collection::class, $result->first());
        self::assertSame([[1, 2], [3]], $result->map->all()->all());
    }

    public function test_pchunk_rejects_non_positive_chunk_sizes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The chunk size must be greater than zero.');

        collect([1, 2, 3])->pchunk(0);
    }

    public function test_psort_returns_a_reindexed_collection(): void
    {
        $result = collect([
            'third' => 3,
            'first' => 1,
            9 => 2,
        ])->psort();

        self::assertSame([1, 2, 3], $result->all());
    }

    public function test_ptovec_and_ptodict_extract_expected_array_shapes(): void
    {
        $collection = collect([
            'name' => 'Taylor',
            2 => 'Laravel',
        ]);

        self::assertSame(['Taylor', 'Laravel'], $collection->ptoVec());
        self::assertSame([
            'name' => 'Taylor',
            2 => 'Laravel',
        ], $collection->ptoDict());
    }
}
