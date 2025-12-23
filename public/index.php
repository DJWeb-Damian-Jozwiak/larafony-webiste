<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Bootstrap the application
    $app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the application
    $app->run();
}catch (\Throwable $e){
    var_dump($e);
}

