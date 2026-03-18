<?php

declare(strict_types=1);

namespace LaravelPsl\Tests\Unit\Type;

use LaravelPsl\Exceptions\CoercionException;
use LaravelPsl\Tests\TestCase;
use LaravelPsl\Type\Coerce;
use Psl\Type;
use Psl\Type\Exception\CoercionException as PslCoercionException;

final class CoerceTest extends TestCase
{
    public function test_it_coerces_values_with_a_thin_value_oriented_api(): void
    {
        $type = Type\shape([
            'name' => Type\non_empty_string(),
            'roles' => Type\vec(Type\string()),
        ]);

        $result = Coerce::value($type, [
            'name' => 'Taylor',
            'roles' => ['admin', 'editor'],
        ]);

        self::assertSame([
            'name' => 'Taylor',
            'roles' => ['admin', 'editor'],
        ], $result);
    }

    public function test_it_wraps_psl_coercion_failures_with_context(): void
    {
        $type = Type\shape([
            'name' => Type\non_empty_string(),
            'roles' => Type\vec(Type\string()),
        ]);

        try {
            Coerce::value($type, [
                'name' => '',
                'roles' => ['admin'],
            ], [
                'source' => 'request',
                'field' => 'user',
            ]);

            self::fail('Expected coercion to fail.');
        } catch (CoercionException $exception) {
            self::assertSame(
                sprintf('Failed coercing value to "%s".', $type->toString()),
                $exception->getMessage(),
            );
            self::assertInstanceOf(PslCoercionException::class, $exception->getPrevious());
            self::assertSame('array', $exception->context()['input_type']);
            self::assertSame($type->toString(), $exception->context()['target_type']);
            self::assertSame('request', $exception->context()['source']);
            self::assertSame('user', $exception->context()['field']);
        }
    }
}
