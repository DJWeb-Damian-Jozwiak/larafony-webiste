<?php

use Larafony\Framework\Web\Middleware\HandleNotFound;

return [
    'before_global' => [
        HandleNotFound::class,
    ],
    'global' => [],
    'after_global' => [],
];