<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Configuration & Environment Variables</h1>
    <p class="lead text-white-50">
        Manage application configuration with PHP files and environment variables using Larafony's built-in configuration system.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Best Practice:</strong> Store sensitive data in <code>.env</code> files, never commit them to version control.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's configuration system provides two layers:
    </p>
    <ul>
        <li><strong>Environment Variables (.env)</strong> - Store sensitive data like API keys, database credentials</li>
        <li><strong>Configuration Files (config/*.php)</strong> - Define structured application settings</li>
    </ul>

    <h2>Environment Variables</h2>

    <h3>Creating .env File</h3>
    <p>
        Create a <code>.env</code> file in your project root:
    </p>

    <pre class="line-numbers"><code class="language-bash"># Application Configuration
APP_NAME=Larafony
APP_URL=https://larafony.local
APP_DEBUG=true

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=larafony
DB_USERNAME=root
DB_PASSWORD=secret

# Mail Configuration
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password</code></pre>

    <h3>Environment File Features</h3>
    <p>The .env parser supports:</p>
    <ul>
        <li><strong>Comments</strong> - Lines starting with <code>#</code></li>
        <li><strong>Quoted values</strong> - Single or double quotes for strings with spaces</li>
        <li><strong>Escape sequences</strong> - <code>\n</code>, <code>\r</code>, <code>\t</code>, <code>\"</code>, <code>\\</code></li>
        <li><strong>No overwriting</strong> - Existing environment variables are never overwritten</li>
    </ul>

    <pre class="line-numbers"><code class="language-bash"># Comments are supported
APP_NAME="My Application"  # Quoted values
APP_KEY='base64:...'       # Single quotes work too

# Escape sequences
APP_MESSAGE="Line 1\nLine 2\tTabbed"

# No quotes needed for simple values
DEBUG=true
PORT=3000</code></pre>

    <h3>Reading Environment Variables</h3>
    <p>
        Use <code>EnvReader</code> to access environment variables with optional default values:
    </p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Config\Environment\EnvReader;

// Read environment variable
$appName = EnvReader::read('APP_NAME');

// With default value
$debug = EnvReader::read('APP_DEBUG', false);
$port = EnvReader::read('PORT', 8000);</code></pre>

    <h2>Configuration Files</h2>

    <h3>Creating Config Files</h3>
    <p>
        Configuration files are stored in the <code>config/</code> directory and return PHP arrays:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php
// config/app.php

declare(strict_types=1);

use Larafony\Framework\Config\Environment\EnvReader;

return [
    'name' => EnvReader::read('APP_NAME', 'Larafony'),
    'url' => EnvReader::read('APP_URL', 'http://localhost'),
    'debug' => EnvReader::read('APP_DEBUG', false),
    'timezone' => 'UTC',
    'locale' => 'en',
];</code></pre>

    <pre class="line-numbers"><code class="language-php">&lt;?php
// config/database.php

declare(strict_types=1);

use Larafony\Framework\Config\Environment\EnvReader;

return [
    'default' => EnvReader::read('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => EnvReader::read('DB_HOST', '127.0.0.1'),
            'port' => (int) EnvReader::read('DB_PORT', '3306'),
            'database' => EnvReader::read('DB_DATABASE', 'larafony'),
            'username' => EnvReader::read('DB_USERNAME', 'root'),
            'password' => EnvReader::read('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
        ],
    ],
];</code></pre>

    <h3>Accessing Configuration</h3>
    <p>
        Use the <code>Config</code> facade with dot notation to access configuration values:
    </p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Web\Config;

// Get configuration value
$appName = Config::get('app.name');

// Get with default value
$timezone = Config::get('app.timezone', 'UTC');

// Get nested values using dot notation
$dbHost = Config::get('database.connections.mysql.host');

// Get entire array
$dbConfig = Config::get('database.connections.mysql');</code></pre>

    <h3>Setting Configuration at Runtime</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Web\Config;

// Set configuration value
Config::set('app.custom_setting', 'value');

// Set nested values
Config::set('cache.stores.redis.host', '127.0.0.1');</code></pre>

    <h2>Bootstrap Process</h2>

    <h3>How Configuration Loads</h3>
    <p>
        The <code>ConfigServiceProvider</code> automatically loads configuration during application bootstrap:
    </p>

    <pre class="line-numbers"><code class="language-php">// bootstrap/app.php
$app->withServiceProviders([
    ConfigServiceProvider::class, // Loads .env and config files
    // ... other providers
]);

// After bootstrap, configuration is available:
Config::get('app.name');</code></pre>

    <p>The loading process:</p>
    <ol>
        <li>Load <code>.env</code> file from project root</li>
        <li>Set environment variables in <code>$_ENV</code>, <code>$_SERVER</code>, and via <code>putenv()</code></li>
        <li>Scan <code>config/</code> directory for PHP files</li>
        <li>Load each config file and store under its filename as key</li>
    </ol>

    <h2>Environment-Specific Configuration</h2>

    <h3>Using .env.example</h3>
    <p>
        Create a <code>.env.example</code> file as a template for your team:
    </p>

    <pre class="line-numbers"><code class="language-bash"># .env.example (committed to git)
APP_NAME=
APP_URL=
APP_DEBUG=

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=</code></pre>

    <p>Team members copy this to <code>.env</code> and fill in their values:</p>

    <pre class="line-numbers"><code class="language-bash">cp .env.example .env
# Edit .env with your local values</code></pre>

    <h3>.gitignore</h3>
    <p>
        Always add <code>.env</code> to your <code>.gitignore</code>:
    </p>

    <pre class="line-numbers"><code class="language-bash"># .gitignore
.env
.env.local
.env.*.local</code></pre>

    <h2>Practical Examples</h2>

    <h3>Example 1: Database Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// In your service or repository
use Larafony\Framework\Web\Config;

class DatabaseConnection
{
    public function connect(): PDO
    {
        $config = Config::get('database.connections.mysql');

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        return new PDO(
            $dsn,
            $config['username'],
            $config['password']
        );
    }
}</code></pre>

    <h3>Example 2: Feature Flags</h3>

    <pre class="line-numbers"><code class="language-php">// config/features.php
return [
    'new_dashboard' => EnvReader::read('FEATURE_NEW_DASHBOARD', false),
    'api_v2' => EnvReader::read('FEATURE_API_V2', false),
    'beta_features' => EnvReader::read('FEATURE_BETA', false),
];

// In your controller
if (Config::get('features.new_dashboard')) {
    return $this->render('dashboard.new');
}

return $this->render('dashboard.classic');</code></pre>

    <h3>Example 3: Service Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// config/services.php
return [
    'stripe' => [
        'key' => EnvReader::read('STRIPE_KEY'),
        'secret' => EnvReader::read('STRIPE_SECRET'),
    ],

    'aws' => [
        'key' => EnvReader::read('AWS_ACCESS_KEY_ID'),
        'secret' => EnvReader::read('AWS_SECRET_ACCESS_KEY'),
        'region' => EnvReader::read('AWS_DEFAULT_REGION', 'us-east-1'),
        'bucket' => EnvReader::read('AWS_BUCKET'),
    ],
];

// Usage
use Larafony\Framework\Web\Config;

$stripeKey = Config::get('services.stripe.key');
$awsRegion = Config::get('services.aws.region');</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Store sensitive data in .env files</li>
            <li>Use EnvReader in config files to read environment variables</li>
            <li>Provide sensible default values</li>
            <li>Use dot notation for nested configuration</li>
            <li>Commit .env.example, never commit .env</li>
            <li>Type cast environment variables (int, bool) in config files</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't commit .env files to version control</li>
            <li>Don't store sensitive data in config files directly</li>
            <li>Don't use EnvReader directly in business logic (use Config instead)</li>
            <li>Don't forget to add .env to .gitignore</li>
        </ul>
    </div>

    <h2>Security Considerations</h2>

    <div class="alert-docs alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>Security Warning:</strong> Never store passwords, API keys, or secrets directly in configuration files.
        Always use environment variables via <code>.env</code> files.
    </div>

    <ul>
        <li><strong>Production Secrets</strong> - Use environment variables set at the OS/container level</li>
        <li><strong>File Permissions</strong> - Ensure .env files are not web-accessible</li>
        <li><strong>Version Control</strong> - Never commit .env to git</li>
        <li><strong>Secret Rotation</strong> - Regularly update sensitive credentials</li>
    </ul>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-hdd me-2 text-primary"></i>Database</h4>
                <p class="text-white-50 mb-3">Learn about Schema Builder and Query Builder for database operations.</p>
                <a href="/docs/schema-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-journal-code me-2 text-primary"></i>Logging</h4>
                <p class="text-white-50 mb-3">Set up PSR-3 compliant logging for your application.</p>
                <a href="/docs/logging" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
