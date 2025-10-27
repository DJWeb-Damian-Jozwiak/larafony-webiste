<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Dependency Injection Container (PSR-11)</h1>
    <p class="lead text-white-50">
        Larafony includes a powerful PSR-11 compliant dependency injection container with automatic autowiring.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>PSR-11 Compliance:</strong> The container follows <code>Psr\Container\ContainerInterface</code> standard for maximum interoperability.
    </div>

    <h2>What is a Container?</h2>
    <p>
        The dependency injection container is the heart of Larafony. It manages class dependencies and performs automatic
        dependency injection through constructor parameters. The container can:
    </p>
    <ul>
        <li><strong>Autowire dependencies</strong> - Automatically resolve constructor dependencies</li>
        <li><strong>Bind values</strong> - Store configuration values (strings, numbers, booleans)</li>
        <li><strong>Register services</strong> - Store service instances or class names</li>
        <li><strong>Dot notation access</strong> - Access nested values using dot syntax</li>
    </ul>

    <h2>Basic Usage</h2>

    <h3>Accessing the Container</h3>
    <p>
        The container is available throughout your application. You can access it in several ways:
    </p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Web\Application;
use Psr\Container\ContainerInterface;

// Method 1: Through Application instance
$app = Application::instance();
$container = $app; // Application implements ContainerInterface

// Method 2: Dependency injection (preferred)
class UserController
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}
}</code></pre>

    <h3>Retrieving Services</h3>
    <p>
        The <code>get()</code> method retrieves services from the container. If the service doesn't exist,
        the container will attempt to autowire it:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Services\EmailService;

// Get a service - will autowire if not registered
$emailService = $container->get(EmailService::class);

// Get with dot notation
$dbHost = $container->get('database.host');</code></pre>

    <h3>Checking Service Existence</h3>

    <pre class="line-numbers"><code class="language-php">if ($container->has(EmailService::class)) {
    $service = $container->get(EmailService::class);
}</code></pre>

    <h2>Binding Values</h2>

    <h3>Simple Value Bindings</h3>
    <p>
        Use <code>bind()</code> to store scalar values (strings, numbers, booleans, null):
    </p>

    <pre class="line-numbers"><code class="language-php">// Bind configuration values
$container->bind('base_path', '/var/www/app');
$container->bind('debug', true);
$container->bind('timeout', 30);

// Retrieve bindings
$basePath = $container->getBinding('base_path'); // '/var/www/app'
$debug = $container->getBinding('debug'); // true</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Note:</strong> <code>bind()</code> only accepts scalar values. For objects or arrays, use <code>set()</code>.
    </div>

    <h2>Registering Services</h2>

    <h3>Using set()</h3>
    <p>
        The <code>set()</code> method registers services, instances, or any values:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Services\EmailService;
use Larafony\Framework\Config\Contracts\ConfigContract;

// Set an instance
$config = new ConfigBase($container);
$container->set(ConfigContract::class, $config);

// Set a class name (will be autowired when retrieved)
$container->set('email', EmailService::class);

// Set with dot notation for nested values
$container->set('database.host', 'localhost');
$container->set('database.port', 3306);</code></pre>

    <h2>Automatic Autowiring</h2>

    <h3>How Autowiring Works</h3>
    <p>
        The container automatically resolves class dependencies by analyzing constructor parameters:
    </p>

    <pre class="line-numbers"><code class="language-php">namespace App\Services;

use App\Repositories\UserRepository;
use Psr\Log\LoggerInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly LoggerInterface $logger
    ) {}
}

// Container automatically resolves dependencies
$userService = $container->get(UserService::class);
// Container will:
// 1. Resolve UserRepository (and its dependencies)
// 2. Resolve LoggerInterface (must be registered)
// 3. Instantiate UserService with both dependencies</code></pre>

    <h3>Requirements for Autowiring</h3>
    <ul>
        <li>Constructor parameters must be type-hinted with classes or interfaces</li>
        <li>Concrete classes are autowired automatically</li>
        <li>Interfaces must be registered in the container first</li>
        <li>Scalar parameters (string, int, etc.) cannot be autowired</li>
    </ul>

    <h2>Service Providers</h2>

    <h3>Registering Services in Providers</h3>
    <p>
        Service providers are the recommended way to register services. They keep your bootstrap code organized:
    </p>

    <pre class="line-numbers"><code class="language-php">namespace App\Providers;

use Larafony\Framework\Container\ServiceProvider;
use Psr\Log\LoggerInterface;
use App\Services\FileLogger;

class LoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interface to implementation
        $this->container->set(
            LoggerInterface::class,
            new FileLogger('/var/log/app.log')
        );
    }
}</code></pre>

    <p>Register the provider in your <code>bootstrap/app.php</code>:</p>

    <pre class="line-numbers"><code class="language-php">$app->registerProviders([
    App\Providers\LoggerServiceProvider::class,
    // ... other providers
]);</code></pre>

    <h2>Practical Examples</h2>

    <h3>Example: Repository Pattern</h3>

    <pre class="line-numbers"><code class="language-php">// Interface
interface UserRepositoryInterface
{
    public function find(int $id): ?User;
}

// Implementation
class DatabaseUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly ConnectionContract $db
    ) {}

    public function find(int $id): ?User
    {
        // Database query logic
    }
}

// Register in provider
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->set(
            UserRepositoryInterface::class,
            DatabaseUserRepository::class // Will be autowired
        );
    }
}

// Use in service
class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {}
}</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Use type-hinted constructor injection</li>
            <li>Register interfaces in service providers</li>
            <li>Keep services focused and single-purpose</li>
            <li>Use dot notation for configuration values</li>
            <li>Leverage autowiring for concrete classes</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't use the container as a service locator</li>
            <li>Don't register services directly in controllers</li>
            <li>Don't use constructor injection for optional dependencies</li>
            <li>Don't create circular dependencies</li>
        </ul>
    </div>

    <h2>API Reference</h2>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Method</th>
                <th>Description</th>
                <th>Returns</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>get(string $id)</code></td>
                <td>Retrieve a service or autowire it</td>
                <td><code>mixed</code></td>
            </tr>
            <tr>
                <td><code>has(string $id)</code></td>
                <td>Check if service is registered</td>
                <td><code>bool</code></td>
            </tr>
            <tr>
                <td><code>set(string $key, mixed $value)</code></td>
                <td>Register a service or value</td>
                <td><code>self</code></td>
            </tr>
            <tr>
                <td><code>bind(string $key, scalar $value)</code></td>
                <td>Bind a scalar value</td>
                <td><code>void</code></td>
            </tr>
            <tr>
                <td><code>getBinding(string $key)</code></td>
                <td>Retrieve a bound scalar value</td>
                <td><code>scalar</code></td>
            </tr>
        </tbody>
    </table>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-gear me-2 text-primary"></i>Configuration</h4>
                <p class="text-white-50 mb-3">Learn how to manage configuration files and environment variables.</p>
                <a href="/docs/config" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-hdd me-2 text-primary"></i>Database</h4>
                <p class="text-white-50 mb-3">Explore the Query Builder and Schema Builder for database operations.</p>
                <a href="/docs/query-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
