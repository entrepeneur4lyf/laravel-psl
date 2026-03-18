<?php

declare(strict_types=1);

namespace LaravelPsl\Exceptions;

use Psl\Type\Exception\CoercionException as PslCoercionException;
use Psl\Type\TypeInterface;

use function get_debug_type;
use function sprintf;

final class CoercionException extends LaravelPslException
{
    /**
     * @template TValue
     *
     * @param  TypeInterface<TValue>  $type
     * @param  array<string, mixed>  $context
     */
    public static function fromPsl(
        TypeInterface $type,
        mixed $value,
        PslCoercionException $previous,
        array $context = [],
    ): self {
        return new self(
            sprintf('Failed coercing value to "%s".', $type->toString()),
            [
                'input_type' => get_debug_type($value),
                'target_type' => $type->toString(),
                'psl_message' => $previous->getMessage(),
                ...$context,
            ],
            $previous,
        );
    }
}
