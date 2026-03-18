<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Feature;

use LaravelPsl\Tests\TestCase;

final class PackageBootTest extends TestCase
{
    public function test_it_merges_default_config(): void
    {
        self::assertTrue(config('psl.features.collection_macros'));
        self::assertTrue(config('psl.features.typed_coercion'));
        self::assertFalse(config('psl.features.concurrency'));

        self::assertFalse(config('psl.concurrency.enabled'));
        self::assertNull(config('psl.concurrency.driver'));
        self::assertTrue(config('psl.concurrency.fail_when_unsupported'));
    }
}
