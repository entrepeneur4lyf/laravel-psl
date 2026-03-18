<?php

declare(strict_types=1);

namespace LaravelPsl\Macros;

use Closure;
use Illuminate\Support\Collection;
use LaravelPsl\Support\CollectionBridge;
use LaravelPsl\Support\DictBridge;

use function Psl\Iter\reduce;
use function Psl\Vec\chunk;
use function Psl\Vec\filter;
use function Psl\Vec\map;
use function Psl\Vec\sort;

final class CollectionMacros
{
    public function pmap(): Closure
    {
        return function (Closure $callback): Collection {
            /** @var Closure(mixed): mixed $callback */
            $collection = CollectionMacroContext::collection($this);

            return new Collection(map(CollectionBridge::toVec($collection->all()), $callback));
        };
    }

    public function pfilter(): Closure
    {
        return function (?Closure $predicate = null): Collection {
            /** @var (Closure(mixed): bool)|null $predicate */
            $collection = CollectionMacroContext::collection($this);

            return new Collection(filter(CollectionBridge::toVec($collection->all()), $predicate));
        };
    }

    public function preduce(): Closure
    {
        return function (Closure $callback, mixed $initial): mixed {
            /** @var Closure(mixed, mixed): mixed $callback */
            $collection = CollectionMacroContext::collection($this);

            return reduce(CollectionBridge::toVec($collection->all()), $callback, $initial);
        };
    }

    public function pchunk(): Closure
    {
        return function (int $size): Collection {
            if ($size < 1) {
                throw new \InvalidArgumentException('The chunk size must be greater than zero.');
            }

            $collection = CollectionMacroContext::collection($this);

            return new Collection(array_map(
                static fn (array $items): Collection => new Collection($items),
                chunk(CollectionBridge::toVec($collection->all()), $size),
            ));
        };
    }

    public function psort(): Closure
    {
        return function (?Closure $comparator = null): Collection {
            /** @var (Closure(mixed, mixed): int)|null $comparator */
            $collection = CollectionMacroContext::collection($this);

            return new Collection(sort(CollectionBridge::toVec($collection->all()), $comparator));
        };
    }

    public function ptoVec(): Closure
    {
        return function (): array {
            $collection = CollectionMacroContext::collection($this);

            return CollectionBridge::toVec($collection->all());
        };
    }

    public function ptoDict(): Closure
    {
        return function (): array {
            $collection = CollectionMacroContext::collection($this);

            return DictBridge::toDict($collection->all());
        };
    }
}
