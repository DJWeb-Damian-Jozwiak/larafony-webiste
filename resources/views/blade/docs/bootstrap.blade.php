<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Application Bootstrap</h1>
    <p class="lead text-white-50">
        Configure your Larafony application with service providers and routes
    </p>

    <h2>The Bootstrap Process</h2>
    <p>
        Larafony applications bootstrap in two phases:
    </p>
    <ol>
        <li><strong>Application Creation</strong> - Create the application instance</li>
        <li><strong>Service Registration</strong> - Register service providers and configure routes</li>
    </ol>

    <h2>bootstrap/app.php</h2>
    <p>
        This is the main bootstrap file for web applications:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

use Larafony\Framework\Config\ServiceProviders\ConfigServiceProvider;
use Larafony\Framework\Database\ServiceProviders\DatabaseServiceProvider;
use Larafony\Framework\ErrorHandler\ServiceProviders\ErrorHandlerServiceProvider;
use Larafony\Framework\Http\ServiceProviders\HttpServiceProvider;
use Larafony\Framework\Routing\ServiceProviders\RouteServiceProvider;
use Larafony\Framework\View\ServiceProviders\ViewServiceProvider;
use Larafony\Framework\Web\ServiceProviders\WebServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

// Create application instance
$app = \Larafony\Framework\Web\Application::instance(
    base_path: dirname(__DIR__)
);

// Register service providers
$app->withServiceProviders([
    ErrorHandlerServiceProvider::class,
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
    HttpServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,
    WebServiceProvider::class,
]);

// Configure routes
$app->withRoutes(function ($router) {
    $router->loadAttributeRoutes(__DIR__ . '/../src/Controllers');
});

return $app;</code></pre>

    <h2>Service Providers</h2>
    <p>
        Service providers register services in the dependency injection container.
        Larafony includes several core service providers:
    </p>

    <h3>ErrorHandlerServiceProvider</h3>
    <p>Registers error and exception handlers</p>

    <h3>ConfigServiceProvider</h3>
    <p>Loads configuration files from the <code>config/</code> directory</p>

    <h3>DatabaseServiceProvider</h3>
    <p>Registers database connection and query builder</p>

    <h3>HttpServiceProvider</h3>
    <p>Registers PSR-7 HTTP message factories</p>

    <h3>RouteServiceProvider</h3>
    <p>Registers the router and route matching</p>

    <h3>ViewServiceProvider</h3>
    <p>Registers the Blade template engine</p>

    <h3>WebServiceProvider</h3>
    <p>Registers web-specific services (controllers, middleware, etc.)</p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Order Matters:</strong> Service providers are registered in the order listed.
        Make sure dependencies are registered before services that use them.
    </div>

    <h2>Route Configuration</h2>
    <p>
        The <code>withRoutes()</code> method configures how routes are discovered:
    </p>

    <pre class="line-numbers"><code class="language-php">$app->withRoutes(function ($router) {
    // Auto-discover routes from controller attributes
    $router->loadAttributeRoutes(__DIR__ . '/../src/Controllers');
});</code></pre>

    <p>
        This scans the <code>src/Controllers</code> directory and automatically registers
        all routes defined with <code>#[Route]</code> attributes.
    </p>

    <h2>Global Middleware</h2>
    <p>
        Register middleware that runs on every request:
    </p>

    <pre class="line-numbers"><code class="language-php">$app->withMiddleware([
    \App\Middleware\LogRequestMiddleware::class,
    \App\Middleware\CorsMiddleware::class,
]);</code></pre>

    <h2>bootstrap/console.php</h2>
    <p>
        For console applications, use a similar bootstrap file:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

use Larafony\Framework\Console\Application as ConsoleApplication;

require_once __DIR__ . '/../vendor/autoload.php';

$app = ConsoleApplication::instance(
    base_path: dirname(__DIR__)
);

// Register service providers (same as web)
$app->withServiceProviders([
    ErrorHandlerServiceProvider::class,
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
    // Console doesn't need HttpServiceProvider or ViewServiceProvider
]);

// Register console commands
$app->withCommands(__DIR__ . '/../src/Console/Commands');

return $app;</code></pre>

    <h2>Running the Application</h2>
    <p>
        The <code>public/index.php</code> file is the entry point:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

// Bootstrap the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the application
$app->run();</code></pre>

    <p>
        The <code>run()</code> method:
    </p>
    <ol>
        <li>Creates a PSR-7 request from PHP globals</li>
        <li>Routes the request to the appropriate controller</li>
        <li>Processes middleware</li>
        <li>Executes the controller</li>
        <li>Sends the response</li>
    </ol>

    <h2>Custom Service Providers</h2>
    <p>
        Create your own service providers for custom services:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Providers;

use Larafony\Framework\DI\ServiceProvider;
use Psr\Container\ContainerInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        // Register your services
        $container->set(MyService::class, function () {
            return new MyService();
        });
    }
}</code></pre>

    <p>Register it in <code>bootstrap/app.php</code>:</p>

    <pre class="line-numbers"><code class="language-php">$app->withServiceProviders([
    // ... other providers
    \App\Providers\AppServiceProvider::class,
]);</code></pre>

    <h2>Environment-Specific Bootstrap</h2>
    <p>
        Configure services differently based on environment:
    </p>

    <pre class="line-numbers"><code class="language-php">$app = Application::instance(base_path: dirname(__DIR__));

// Register base providers
$app->withServiceProviders([
    ErrorHandlerServiceProvider::class,
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
]);

// Environment-specific configuration
if (env('APP_ENV') === 'production') {
    // Production-only services
    $app->withServiceProviders([
        CacheServiceProvider::class,
    ]);
} else {
    // Development-only services
    $app->withServiceProviders([
        DebugServiceProvider::class,
    ]);
}

// Continue with common providers
$app->withServiceProviders([
    HttpServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,
    WebServiceProvider::class,
]);

return $app;</code></pre>

    <h2>Configuration Files</h2>
    <p>
        Service providers often load configuration from <code>config/</code> files:
    </p>

    <h3>config/database.php</h3>
    <pre class="line-numbers"><code class="language-php">&lt;?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'larafony'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
        ],
    ],
];</code></pre>

    <h3>config/view.php</h3>
    <pre class="line-numbers"><code class="language-php">&lt;?php

return [
    'paths' => [
        __DIR__ . '/../resources/views',
    ],

    'cache' => [
        'enabled' => env('VIEW_CACHE_ENABLED', true),
        'path' => __DIR__ . '/../storage/cache/views',
    ],

    'blade' => [
        'directives' => [
            // Custom Blade directives
        ],
    ],
];</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Tip:</strong> Use the <code>env()</code> helper to read from <code>.env</code> files,
        and always provide sensible defaults as the second parameter.
    </div>

    <h2>Testing Bootstrap</h2>
    <p>
        For tests, create a minimal bootstrap:
    </p>

    <pre class="line-numbers"><code class="language-php">// tests/bootstrap.php
$app = Application::instance(base_path: dirname(__DIR__));

$app->withServiceProviders([
    // Only providers needed for testing
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
]);

return $app;</code></pre>

    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/structure">Learn about project structure →</a></li>
        <li><a href="/docs/middleware">Learn about global middleware →</a></li>
        <li><a href="/docs/controllers">Learn about controllers →</a></li>
    </ul>
</x-docs-layout>
