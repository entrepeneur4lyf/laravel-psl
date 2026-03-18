<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Feature;

use LaravelPsl\Tests\TestCase;

final class PackageBootTest extends TestCase
{
    public function test_it_merges_default_config(): void
    {
        self::assertTrue(config('psl.features.collection_macros'));
    }

    public function test_public_v1_config_contains_only_shipped_features(): void
    {
        self::assertSame([
            'features' => [
                'collection_macros' => true,
            ],
        ], config('psl'));
    }
}
