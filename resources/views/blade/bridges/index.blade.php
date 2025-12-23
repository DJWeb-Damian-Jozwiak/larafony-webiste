<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Bridge Packages</h1>
    <p class="lead text-white-50">
        Official bridge packages to integrate popular PHP libraries with Larafony Framework.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Drop-in Replacements:</strong> Each bridge replaces Larafony's native implementation by swapping the service provider. No code changes required - same interfaces, different engine.
    </div>

    <h2>Available Bridges</h2>

    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cloud-arrow-down text-primary me-2"></i>Guzzle HTTP</h5>
                    <p class="card-text text-white-50">Replace native cURL client with Guzzle for async requests, middleware, and advanced HTTP features.</p>
                    <a href="/bridges/guzzle" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-journal-text text-primary me-2"></i>Monolog</h5>
                    <p class="card-text text-white-50">Replace native Logger with Monolog for 50+ handlers, processors, and formatters.</p>
                    <a href="/bridges/monolog" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-envelope-paper text-primary me-2"></i>Symfony Mailer</h5>
                    <p class="card-text text-white-50">Replace native SMTP with Symfony Mailer for SES, Mailgun, SendGrid, and more.</p>
                    <a href="/bridges/symfony-mailer" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-folder2-open text-primary me-2"></i>Flysystem</h5>
                    <p class="card-text text-white-50">Unified filesystem abstraction for local, S3, FTP, SFTP, and more backends.</p>
                    <a href="/bridges/flysystem" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-date text-primary me-2"></i>Carbon Clock</h5>
                    <p class="card-text text-white-50">PSR-20 Clock implementation with Carbon for powerful date/time manipulation.</p>
                    <a href="/bridges/carbon" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-earmark-text text-primary me-2"></i>PHP dotenv</h5>
                    <p class="card-text text-white-50">Enhanced .env parsing with variable expansion, multiline values, and validation.</p>
                    <a href="/bridges/phpdotenv" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-earmark-code text-primary me-2"></i>Twig Templates</h5>
                    <p class="card-text text-white-50">Use Twig templating engine as an alternative to Blade.</p>
                    <a href="/bridges/twig" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-braces text-primary me-2"></i>Smarty Templates</h5>
                    <p class="card-text text-white-50">Use Smarty templating engine as an alternative to Blade.</p>
                    <a href="/bridges/smarty" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bug text-primary me-2"></i>PHP DebugBar</h5>
                    <p class="card-text text-white-50">Replace native DebugBar with maximebf/debugbar for visual in-browser debugging.</p>
                    <a href="/bridges/debugbar" class="btn btn-outline-primary btn-sm">Learn more →</a>
                </div>
            </div>
        </div>
    </div>

    <h2>How Bridges Work</h2>
    <p>
        Larafony bridges follow a simple pattern: swap the service provider in your <code>bootstrap.php</code>
        and the bridge takes over. Your application code remains unchanged because bridges implement the same interfaces.
    </p>

    <pre class="line-numbers"><code class="language-php">// bootstrap.php
$app->withServiceProviders([
    // Comment out native provider
    // Larafony\Framework\Log\ServiceProviders\LogServiceProvider::class,

    // Add bridge provider instead
    Larafony\Log\Monolog\ServiceProviders\MonologServiceProvider::class,
]);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>PSR Standards:</strong> All bridges implement PSR interfaces (PSR-3, PSR-18, PSR-20), ensuring compatibility with any PSR-compliant library.
    </div>
</x-bridges-layout>
