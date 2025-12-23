<?php

declare(strict_types=1);

use Larafony\Framework\Cache\ServiceProviders\CacheServiceProvider;
use Larafony\Framework\Config\ServiceProviders\ConfigServiceProvider;
use Larafony\Framework\Database\ServiceProviders\DatabaseServiceProvider;
use Larafony\Framework\ErrorHandler\ServiceProviders\ErrorHandlerServiceProvider;
use Larafony\Framework\Http\ServiceProviders\HttpServiceProvider;
use Larafony\Framework\MCP\ServiceProviders\McpServiceProvider;
use Larafony\Framework\Routing\ServiceProviders\RouteServiceProvider;
use Larafony\Framework\View\ServiceProviders\ViewServiceProvider;
use Larafony\Framework\Web\ServiceProviders\WebServiceProvider;
use Larafony\McpAssistant\McpAssistantServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = \Larafony\Framework\Web\Application::instance(base_path: dirname(__DIR__));

$app->withServiceProviders([
    ConfigServiceProvider::class,
    CacheServiceProvider::class,
    DatabaseServiceProvider::class,
    HttpServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,
    WebServiceProvider::class,
    McpServiceProvider::class,
    McpAssistantServiceProvider::class,
    ErrorHandlerServiceProvider::class,
]);

$app->withRoutes(function ($router) {
    $router->loadAttributeRoutes(__DIR__ . '/../src/Controllers');
    // Load MCP HTTP endpoint from framework
    $router->loadAttributeRoutes(__DIR__ . '/../vendor/larafony/core/src/Larafony/MCP/Http');
});

return $app;

