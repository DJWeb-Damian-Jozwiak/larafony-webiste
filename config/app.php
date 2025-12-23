<?php

declare(strict_types=1);

use Larafony\Framework\Config\Environment\EnvReader;

return [
    'name' => 'Larafony',
    'env' => EnvReader::read('APP_ENV', 'production'),
    'debug' => EnvReader::read('APP_DEBUG', 'false') === 'true',
    'url' => EnvReader::read('APP_URL', 'https://larafony.com'),
    'timezone' => 'UTC',
    'base_path' => dirname(__DIR__),
    'src_path' => 'src',
];
