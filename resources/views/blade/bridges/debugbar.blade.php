<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">PHP DebugBar Bridge</h1>
    <p class="lead text-white-50">
        Integrate PHP DebugBar for visual in-browser debugging.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/debugbar-php</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\DebugBar\Php\ServiceProviders\PhpDebugBarServiceProvider;

$app->withServiceProviders([
    PhpDebugBarServiceProvider::class
]);</code></pre>

    <h2>Usage</h2>
    <p>The debug bar appears automatically at the bottom of pages in development mode. It shows:</p>
    <ul>
        <li><strong>Messages</strong> - Log messages and debug output</li>
        <li><strong>Timeline</strong> - Request duration and performance</li>
        <li><strong>Exceptions</strong> - Error details and stack traces</li>
        <li><strong>Database</strong> - SQL queries and execution time</li>
        <li><strong>Request</strong> - Headers, parameters, session data</li>
        <li><strong>Memory</strong> - Peak memory usage</li>
    </ul>

    <h2>Adding Messages</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\DebugBar\Php\DebugBar;

$debugbar = $container->get(DebugBar::class);

$debugbar->info('User logged in');
$debugbar->warning('Cache miss for key: users');
$debugbar->error('Payment failed');

// With context
$debugbar->debug('Query result', ['count' => 42]);</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Visual debugging</strong> - In-browser toolbar</li>
        <li><strong>Query monitoring</strong> - All SQL queries with timing</li>
        <li><strong>Performance metrics</strong> - Memory, time, timeline</li>
        <li><strong>Request inspection</strong> - Headers, POST, GET, cookies</li>
        <li><strong>Exception display</strong> - Full stack traces</li>
    </ul>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Production Warning:</strong> Disable the debug bar in production to prevent exposing sensitive information. Set <code>APP_DEBUG=false</code> in your <code>.env</code> file.
    </div>
</x-bridges-layout>
