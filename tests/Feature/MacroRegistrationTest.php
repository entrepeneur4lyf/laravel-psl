<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Feature;

use Illuminate\Support\Collection;
use LaravelPsl\LaravelPslServiceProvider;
use LaravelPsl\Tests\TestCase;

final class MacroRegistrationTest extends TestCase
{
    protected function tearDown(): void
    {
        Collection::flushMacros();

        parent::tearDown();
    }

    public function test_it_registers_collection_macros_when_enabled(): void
    {
        Collection::flushMacros();
        config()->set('psl.features.collection_macros', true);

        (new LaravelPslServiceProvider($this->app))->boot();

        self::assertTrue(Collection::hasMacro('pmap'));
        self::assertTrue(Collection::hasMacro('pfilter'));
        self::assertTrue(Collection::hasMacro('preduce'));
        self::assertTrue(Collection::hasMacro('pchunk'));
        self::assertTrue(Collection::hasMacro('psort'));
        self::assertTrue(Collection::hasMacro('ptoVec'));
        self::assertTrue(Collection::hasMacro('ptoDict'));
    }

    public function test_it_does_not_register_collection_macros_when_disabled(): void
    {
        Collection::flushMacros();
        config()->set('psl.features.collection_macros', false);

        (new LaravelPslServiceProvider($this->app))->boot();

        self::assertFalse(Collection::hasMacro('pmap'));
        self::assertFalse(Collection::hasMacro('pfilter'));
        self::assertFalse(Collection::hasMacro('preduce'));
        self::assertFalse(Collection::hasMacro('pchunk'));
        self::assertFalse(Collection::hasMacro('psort'));
        self::assertFalse(Collection::hasMacro('ptoVec'));
        self::assertFalse(Collection::hasMacro('ptoDict'));
    }
}
