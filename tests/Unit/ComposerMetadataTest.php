<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Unit;

use LaravelPsl\Tests\TestCase;

final class ComposerMetadataTest extends TestCase
{
    public function test_it_exposes_package_discovery_metadata(): void
    {
        /** @var array{
         *     extra: array{
         *         laravel: array{
         *             providers: list<string>
         *         }
         *     }
         * } $composer
         */
        $composer = json_decode(
            file_get_contents(__DIR__.'/../../composer.json') ?: '{}',
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertContains(
            'LaravelPsl\\LaravelPslServiceProvider',
            $composer['extra']['laravel']['providers'],
        );
    }
}
