<?php

declare(strict_types=1);

return [
    'features' => [
        'collection_macros' => true,
        'typed_coercion' => true,
        'concurrency' => false,
    ],

    'concurrency' => [
        'enabled' => false,
        // Null means "use Laravel's configured default" once concurrency support is implemented.
        'driver' => null,
        'fail_when_unsupported' => true,
    ],
];
