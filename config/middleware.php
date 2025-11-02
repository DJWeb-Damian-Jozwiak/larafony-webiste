<?php

use App\Middleware\HandleInternalError;
use App\Middleware\HandleNotFound;
use Larafony\Framework\Http\Middleware\InertiaMiddleware;

return [
    'before_global' => [
    ],
    'global' => [
        InertiaMiddleware::class,
    ],
    'after_global' => [],
];