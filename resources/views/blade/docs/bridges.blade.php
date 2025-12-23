<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Bridge Packages</h1>
    <p class="lead text-white-50">
        Official bridge packages to integrate popular PHP libraries with Larafony Framework.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Drop-in Replacements:</strong> Each bridge replaces Larafony's native implementation by swapping the service provider. No code changes required.
    </div>

    <h2>Available Bridges</h2>

    <h3><i class="bi bi-calendar-date text-primary me-2"></i>Carbon Clock</h3>
    <p>Replace native Clock with Carbon for advanced date/time manipulation.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/clock-carbon</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Clock\Carbon\ServiceProviders\CarbonServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Clock\Carbon\CarbonClock;

$clock = CarbonClock::fromTimezone('Europe/Warsaw');
$now = $clock->now();           // CarbonImmutable
$tomorrow = $now->addDay();
$formatted = $now->diffForHumans(); // "just now"</code></pre>

    <h3><i class="bi bi-journal-text text-primary me-2"></i>Monolog Logger</h3>
    <p>Replace native Logger with Monolog for advanced logging features.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/log-monolog</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Log\Monolog\ServiceProviders\MonologServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">use Psr\Log\LoggerInterface;

// Inject via constructor or container
$logger->info('User logged in', ['user_id' => 123]);
$logger->error('Payment failed', ['order' => $orderId]);</code></pre>

    <h3><i class="bi bi-cloud-arrow-down text-primary me-2"></i>Guzzle HTTP Client</h3>
    <p>Replace native cURL client with Guzzle for advanced HTTP features.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/http-guzzle</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Http\Guzzle\ServiceProviders\GuzzleServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">use Psr\Http\Client\ClientInterface;

// Inject via constructor or container
$response = $client->sendRequest($request);

// Or use factory
use Larafony\Framework\Http\Client\HttpClientFactory;
$client = HttpClientFactory::instance();</code></pre>

    <h3><i class="bi bi-envelope-paper text-primary me-2"></i>Symfony Mailer</h3>
    <p>Replace native SMTP with Symfony Mailer for advanced mail transports.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/mail-symfony</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Mail\Symfony\ServiceProviders\SymfonyMailerServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">// Uses same Mailable API - no code changes needed
$mailer->send(new WelcomeEmail($user));</code></pre>

    <h3><i class="bi bi-file-earmark-code text-primary me-2"></i>Twig Templates</h3>
    <p>Replace Blade with Twig template engine.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/view-twig</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\View\Twig\ServiceProviders\TwigServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-twig">{# resources/views/twig/welcome.twig #}
{% extends "layout.twig" %}
{% block content %}
    &lt;h1&gt;Hello {{ name }}&lt;/h1&gt;
{% endblock %}</code></pre>

    <h3><i class="bi bi-braces text-primary me-2"></i>Smarty Templates</h3>
    <p>Replace Blade with Smarty template engine.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/view-smarty</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\View\Smarty\ServiceProviders\SmartyServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-smarty">{* resources/views/smarty/welcome.tpl *}
{extends file="layout.tpl"}
{block name="content"}
    &lt;h1&gt;Hello {$name}&lt;/h1&gt;
{/block}</code></pre>

    <h3><i class="bi bi-bug text-primary me-2"></i>PHP Debug Bar</h3>
    <p>Replace native DebugBar with maximebf/debugbar.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/debugbar-php</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\DebugBar\Php\ServiceProviders\PhpDebugBarServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">// Automatic - debug bar appears on pages in development mode</code></pre>

    <h3><i class="bi bi-broadcast-pin text-primary me-2"></i>ReactPHP WebSocket</h3>
    <p>Replace native Fibers WebSocket engine with ReactPHP for production scale.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/websocket-react</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\WebSocket\ReactWebSocketServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">// Same WebSocket API - ReactPHP handles the event loop
php bin/larafony websocket:serve --port=8080</code></pre>

    <h3><i class="bi bi-file-earmark-text text-primary me-2"></i>Phpdotenv Environment</h3>
    <p>Replace native EnvironmentLoader with vlucas/phpdotenv for enhanced .env parsing.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/env-phpdotenv</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Env\Phpdotenv\ServiceProviders\PhpdotenvServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">// Automatic - environment variables loaded from .env file
// Supports: nested variables, multiline values, variable expansion
$apiKey = $_ENV['API_KEY'];  // ${BASE_URL}/api expands correctly</code></pre>

    <h3><i class="bi bi-folder2-open text-primary me-2"></i>Flysystem Storage</h3>
    <p>Replace or extend native storage with League Flysystem for multiple backends.</p>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/storage-flysystem</code></pre>
    <p><strong>Service Provider:</strong></p>
    <pre class="line-numbers"><code class="language-php">Larafony\Storage\Flysystem\ServiceProviders\FlysystemServiceProvider::class</code></pre>
    <p><strong>Usage:</strong></p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Storage\Flysystem\FlysystemStorage;
use Larafony\Storage\Flysystem\FlysystemFactory;

// Local storage
$storage = FlysystemStorage::local('/var/www/storage');
$storage->put('uploads/photo.jpg', $content);
$content = $storage->get('uploads/photo.jpg');

// Multi-disk via factory (local, s3, ftp, sftp)
$factory = $container->get(FlysystemFactory::class);
$s3Disk = $factory->disk('s3');
$s3Disk->put('backups/db.sql', $dump);</code></pre>

    <h2>How Bridges Work</h2>
    <p>
        Larafony bridges follow a simple pattern: swap the service provider in your <code>config/providers.php</code>
        and the bridge takes over. Your application code remains unchanged because bridges implement the same interfaces.
    </p>

    <pre class="line-numbers"><code class="language-php">// config/providers.php
return [
    // Comment out or remove native provider
    // Larafony\Framework\Log\ServiceProviders\LogServiceProvider::class,

    // Add bridge provider
    Larafony\Log\Monolog\ServiceProviders\MonologServiceProvider::class,
];</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>PSR Standards:</strong> All bridges implement PSR interfaces (PSR-3, PSR-18, PSR-20), ensuring compatibility with any PSR-compliant library.
    </div>
</x-docs-layout>
