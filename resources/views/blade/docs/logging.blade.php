<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Logging (PSR-3)</h1>
    <p class="lead text-white-50">
        Track application events and errors with Larafony's PSR-3 compliant logging system.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>PSR-3 Compliant:</strong> Fully implements <code>Psr\Log\LoggerInterface</code> with all eight log levels.
    </div>

    <h2>Overview</h2>
    <p>
        The logging system provides:
    </p>
    <ul>
        <li><strong>PSR-3 Compliant</strong> - Standard logging interface</li>
        <li><strong>Multiple Handlers</strong> - File, database, custom handlers</li>
        <li><strong>Multiple Formats</strong> - Text, JSON, XML</li>
        <li><strong>Log Rotation</strong> - Automatic daily rotation with cleanup</li>
        <li><strong>Context Support</strong> - Add contextual data to logs</li>
    </ul>

    <h2>Basic Usage</h2>

    <h3>Simple Logging</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Log;

// Info level
Log::info('User logged in', ['user_id' => 123]);

// Error level
Log::error('Database connection failed', [
    'database' => 'production',
    'error' => $exception->getMessage()
]);

// Debug level
Log::debug('Cache miss', ['key' => 'user:123:profile']);</code></pre>

    <h3>All Log Levels</h3>

    <pre class="line-numbers"><code class="language-php">// Emergency: System is unusable
Log::emergency('System is down');

// Alert: Action must be taken immediately
Log::alert('Disk space critical');

// Critical: Critical conditions
Log::critical('Application crashed');

// Error: Runtime errors
Log::error('Failed to process payment');

// Warning: Exceptional occurrences that are not errors
Log::warning('High memory usage detected');

// Notice: Normal but significant events
Log::notice('Configuration updated');

// Info: Interesting events
Log::info('User registered');

// Debug: Detailed debug information
Log::debug('Query executed', ['sql' => $sql]);</code></pre>

    <h2>Log Context</h2>

    <h3>Adding Context Data</h3>

    <pre class="line-numbers"><code class="language-php">// Context provides additional information
Log::info('Order placed', [
    'order_id' => 12345,
    'user_id' => 67,
    'total' => 99.99,
    'items' => ['product_1', 'product_2']
]);

// Context with exception
try {
    // Something that might fail
} catch (Exception $e) {
    Log::error('Operation failed', [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}</code></pre>

    <h3>Placeholder Interpolation</h3>

    <pre class="line-numbers"><code class="language-php">// PSR-3 placeholder syntax
Log::info('User {username} performed {action}', [
    'username' => 'john.doe',
    'action' => 'logout'
]);
// Output: "User john.doe performed logout"

// Works with all types
Log::warning('Failed login from {ip} with data {data}', [
    'ip' => '10.0.0.1',
    'data' => ['username' => 'admin', 'attempts' => 5]
]);</code></pre>

    <h2>Configuration</h2>

    <h3>File Handler Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// config/logging.php
return [
    'channels' => [
        [
            'handler' => 'file',
            'path' => storage_path('logs/app.log'),
            'formatter' => 'text',
            'max_days' => 14
        ],
        [
            'handler' => 'file',
            'path' => storage_path('logs/errors.log'),
            'formatter' => 'json',
            'max_days' => 30
        ]
    ]
];</code></pre>

    <h3>Creating Logger Programmatically</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Logger;
use Larafony\Framework\Log\Handlers\FileHandler;
use Larafony\Framework\Log\Formatters\JsonFormatter;
use Larafony\Framework\Log\Formatters\TextFormatter;
use Larafony\Framework\Log\Rotators\DailyRotator;

$logger = new Logger([
    // JSON logs with 30-day rotation
    new FileHandler(
        logPath: '/var/log/app/application.log',
        formatter: new JsonFormatter(),
        rotator: new DailyRotator(maxDays: 30)
    ),

    // Text logs with 7-day rotation
    new FileHandler(
        logPath: '/var/log/app/debug.log',
        formatter: new TextFormatter(),
        rotator: new DailyRotator(maxDays: 7)
    ),
]);</code></pre>

    <h2>Handlers</h2>

    <h3>File Handler</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Handlers\FileHandler;
use Larafony\Framework\Log\Formatters\TextFormatter;

$handler = new FileHandler(
    logPath: '/var/log/app/app.log',
    formatter: new TextFormatter()
);

$logger = new Logger([$handler]);</code></pre>

    <h3>Database Handler</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Handlers\DatabaseHandler;

// Stores logs in database
$handler = new DatabaseHandler();

$logger = new Logger([$handler]);

// Logs are automatically saved to the database
$logger->error('Critical error', ['details' => 'Some error']);</code></pre>

    <h2>Formatters</h2>

    <h3>Text Formatter</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Formatters\TextFormatter;

$formatter = new TextFormatter();

// Output format:
// [2025-01-15 14:30:45] ERROR: Database connection failed
// Context: {"database":"production","error":"Connection timeout"}
// Metadata: {"timestamp":"2025-01-15T14:30:45+00:00"}</code></pre>

    <h3>JSON Formatter</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Formatters\JsonFormatter;

$formatter = new JsonFormatter();

// Output format (pretty-printed JSON):
// {
//   "level": "error",
//   "message": "Database connection failed",
//   "context": {
//     "database": "production",
//     "error": "Connection timeout"
//   },
//   "metadata": {
//     "timestamp": "2025-01-15T14:30:45+00:00"
//   }
// }</code></pre>

    <h3>XML Formatter</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Formatters\XmlFormatter;

$formatter = new XmlFormatter();

// Output format:
// <?xml version="1.0"?>
// <log>
//   <level>error</level>
//   <message>Database connection failed</message>
//   <context>
//     <database>production</database>
//     <error>Connection timeout</error>
//   </context>
// </log></code></pre>

    <h2>Log Rotation</h2>

    <h3>Daily Rotation</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Rotators\DailyRotator;

// Rotate daily, keep logs for 14 days
$rotator = new DailyRotator(maxDays: 14);

// Logs are automatically rotated:
// app.log          (current)
// app-2025-01-14.log
// app-2025-01-13.log
// ...
// (older logs automatically deleted)</code></pre>

    <h2>Practical Examples</h2>

    <h3>Example 1: Application Logging</h3>

    <pre class="line-numbers"><code class="language-php">class UserService
{
    public function createUser(array $data): User
    {
        Log::info('Creating new user', [
            'email' => $data['email']
        ]);

        try {
            $user = User::create($data);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return $user;
        } catch (Exception $e) {
            Log::error('Failed to create user', [
                'email' => $data['email'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}</code></pre>

    <h3>Example 2: API Request Logging</h3>

    <pre class="line-numbers"><code class="language-php">class ApiMiddleware
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $startTime = microtime(true);

        Log::info('API request started', [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'user_agent' => $request->getHeaderLine('User-Agent')
        ]);

        try {
            $response = $this->next($request);

            $duration = microtime(true) - $startTime;

            Log::info('API request completed', [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'status' => $response->getStatusCode(),
                'duration' => round($duration * 1000, 2) . 'ms'
            ]);

            return $response;
        } catch (Exception $e) {
            Log::error('API request failed', [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}</code></pre>

    <h3>Example 3: Performance Monitoring</h3>

    <pre class="line-numbers"><code class="language-php">class PerformanceLogger
{
    public static function logSlowQuery(string $sql, float $duration): void
    {
        if ($duration > 1.0) {
            Log::warning('Slow query detected', [
                'sql' => $sql,
                'duration' => $duration,
                'threshold' => 1.0
            ]);
        }
    }

    public static function logHighMemoryUsage(): void
    {
        $memory = memory_get_usage(true) / 1024 / 1024;

        if ($memory > 100) {
            Log::warning('High memory usage', [
                'memory_mb' => round($memory, 2),
                'threshold' => 100
            ]);
        }
    }
}</code></pre>

    <h2>Custom Handler</h2>

    <h3>Creating Custom Handler</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Log\Contracts\HandlerContract;
use Larafony\Framework\Log\Message;

class SlackHandler implements HandlerContract
{
    public function __construct(
        private readonly string $webhookUrl,
        private readonly string $channel
    ) {}

    public function handle(Message $message): void
    {
        // Only send critical logs to Slack
        if ($message->level->value !== 'critical') {
            return;
        }

        $payload = json_encode([
            'channel' => $this->channel,
            'text' => $message->message,
            'attachments' => [[
                'color' => 'danger',
                'fields' => [
                    ['title' => 'Level', 'value' => $message->level->value],
                    ['title' => 'Time', 'value' => $message->metadata->timestamp]
                ]
            ]]
        ]);

        // Send to Slack webhook
        $ch = curl_init($this->webhookUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);
    }
}

// Usage
$logger = new Logger([
    new FileHandler('/var/log/app.log', new TextFormatter()),
    new SlackHandler('https://hooks.slack.com/...', '#alerts')
]);</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Use appropriate log levels (info, error, debug, etc.)</li>
            <li>Add context to provide useful information</li>
            <li>Use structured logging (arrays) instead of string concatenation</li>
            <li>Implement log rotation to manage disk space</li>
            <li>Log exceptions with stack traces</li>
            <li>Use JSON formatter for log aggregation tools</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't log sensitive data (passwords, API keys, credit cards)</li>
            <li>Don't log in tight loops (performance impact)</li>
            <li>Don't use wrong log levels (debug for errors, etc.)</li>
            <li>Don't forget to implement log rotation in production</li>
        </ul>
    </div>

    <h2>Security Considerations</h2>

    <div class="alert-docs alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>Security Warning:</strong> Never log sensitive data like passwords, API keys, or credit card numbers.
    </div>

    <pre class="line-numbers"><code class="language-php">// WRONG - Logs sensitive data
Log::info('User login', [
    'email' => $email,
    'password' => $password  // NEVER DO THIS!
]);

// CORRECT - Exclude sensitive data
Log::info('User login attempt', [
    'email' => $email,
    'ip' => $request->getAttribute('ip_address')
]);</code></pre>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-gear me-2 text-primary"></i>Configuration</h4>
                <p class="text-white-50 mb-3">Configure logging handlers and formatters.</p>
                <a href="/docs/config" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-box me-2 text-primary"></i>Container</h4>
                <p class="text-white-50 mb-3">Use dependency injection for logger instances.</p>
                <a href="/docs/container" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
