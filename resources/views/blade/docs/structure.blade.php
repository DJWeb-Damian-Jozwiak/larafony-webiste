<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Project Structure</h1>
    <p class="lead text-white-50">
        Understanding how Larafony projects are organized
    </p>

    <h2>Directory Structure</h2>
    <p>
        Larafony follows a clean, intuitive structure inspired by modern PHP frameworks.
        Here's what each directory contains:
    </p>

    <pre class="line-numbers"><code class="language-bash">my-app/
├── bootstrap/          # Application bootstrap files
│   ├── app.php        # Web application bootstrap
│   └── console.php    # Console application bootstrap
├── config/            # Configuration files
│   ├── database.php   # Database configuration
│   └── view.php       # View engine configuration
├── database/
│   ├── migrations/    # Database migrations
│   └── seeders/       # Database seeders
├── public/
│   └── index.php      # Application entry point
├── resources/
│   └── views/         # Blade templates
├── src/
│   ├── Controllers/   # HTTP controllers
│   ├── Models/        # ORM models
│   ├── DTOs/          # Data Transfer Objects
│   ├── Middleware/    # HTTP middleware
│   ├── Console/       # Console commands
│   └── View/          # View components
├── storage/
│   ├── cache/         # Compiled views & cache
│   └── logs/          # Application logs
├── .env               # Environment variables
├── .env.example       # Example environment file
└── composer.json      # Dependencies</code></pre>

    <h2>Bootstrap Directory</h2>
    <p>
        The <code>bootstrap/</code> directory contains files that bootstrap your application.
    </p>

    <h3>bootstrap/app.php</h3>
    <p>
        This file creates and configures the web application instance. It registers service providers
        and sets up route discovery:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

use Larafony\Framework\Web\Application;

$app = Application::instance(base_path: dirname(__DIR__));

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

// Auto-discover routes from controller attributes
$app->withRoutes(function ($router) {
    $router->loadAttributeRoutes(__DIR__ . '/../src/Controllers');
});

return $app;</code></pre>

    <h3>bootstrap/console.php</h3>
    <p>
        Similar to <code>app.php</code>, but for console commands. It bootstraps the console application
        and registers command directories.
    </p>

    <h2>Config Directory</h2>
    <p>
        Configuration files live in <code>config/</code>. These files return arrays of configuration options.
    </p>

    <h3>config/database.php</h3>
    <p>Database connection settings:</p>

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
            'collation' => 'utf8mb4_unicode_ci',
        ],
    ],
];</code></pre>

    <h2>Public Directory</h2>
    <p>
        The <code>public/</code> directory contains the front controller (<code>index.php</code>) and assets.
        This is your document root—all requests go through <code>index.php</code>.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

require_once __DIR__ . '/../bootstrap/app.php';

$app->run();</code></pre>

    <h2>Src Directory</h2>
    <p>
        Your application code lives in <code>src/</code>. This follows the PSR-4 autoloading standard
        with the <code>App\</code> namespace.
    </p>

    <h3>Controllers</h3>
    <p>
        HTTP controllers with attribute-based routing. See the <a href="/docs/controllers">Controllers & Routing</a> guide.
    </p>

    <h3>Models</h3>
    <p>
        ORM models with attribute-based relationships. See the <a href="/docs/models">Models & Relationships</a> guide.
    </p>

    <h3>DTOs</h3>
    <p>
        Data Transfer Objects for validation. See the <a href="/docs/validation">DTO Validation</a> guide.
    </p>

    <h3>Middleware</h3>
    <p>
        PSR-15 middleware classes. See the <a href="/docs/middleware">Middleware</a> guide.
    </p>

    <h2>Storage Directory</h2>
    <p>
        The <code>storage/</code> directory contains compiled Blade templates, file caches, and logs.
        Make sure this directory is writable.
    </p>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Permissions:</strong> The <code>storage/</code> directory must be writable by your web server.
    </div>

    <h2>Environment Configuration</h2>
    <p>
        The <code>.env</code> file contains environment-specific configuration. Never commit this file to version control.
    </p>

    <pre class="line-numbers"><code class="language-bash">APP_NAME=Larafony
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=larafony
DB_USERNAME=root
DB_PASSWORD=

VIEW_CACHE_ENABLED=true</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Tip:</strong> Copy <code>.env.example</code> to <code>.env</code> and customize it for your environment.
    </div>

    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/models">Learn about Models & Relationships →</a></li>
        <li><a href="/docs/controllers">Learn about Controllers & Routing →</a></li>
        <li><a href="/docs/bootstrap">Learn about Application Bootstrap →</a></li>
    </ul>
</x-docs-layout>
