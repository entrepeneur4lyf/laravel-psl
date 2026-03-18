<?php

declare(strict_types=1);

namespace LaravelPsl\Type;

use LaravelPsl\Exceptions\CoercionException;
use Psl\Type\Exception\CoercionException as PslCoercionException;
use Psl\Type\TypeInterface;

final class Coerce
{
    /**
     * @template TValue
     *
     * @param  TypeInterface<TValue>  $type
     * @param  array<string, mixed>  $context
     * @return TValue
     *
     * @throws CoercionException
     */
    public static function value(TypeInterface $type, mixed $input, array $context = []): mixed
    {
        try {
            return $type->coerce($input);
        } catch (PslCoercionException $exception) {
            throw CoercionException::fromPsl($type, $input, $exception, $context);
        }
    }
}
