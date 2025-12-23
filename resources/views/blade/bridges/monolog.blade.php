<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Monolog Logging Bridge</h1>
    <p class="lead text-white-50">
        Professional logging with Monolog - multiple channels, 50+ handlers, and PSR-3 compatibility.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/log-monolog</code></pre>

    <h2>Configuration</h2>
    <p>Register the service provider in your <code>bootstrap.php</code>:</p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Log\Monolog\ServiceProviders\MonologServiceProvider;

$app->withServiceProviders([
    MonologServiceProvider::class
]);</code></pre>

    <p>Create <code>config/logging.php</code>:</p>
    <pre class="line-numbers"><code class="language-php">return [
    'default' => 'stack',

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'slack'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/app.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/app.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'level' => 'critical',
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],
    ],
];</code></pre>

    <h2>Basic Usage</h2>
    <pre class="line-numbers"><code class="language-php">use Psr\Log\LoggerInterface;
use Larafony\Framework\Web\Controller;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Http\Factories\ResponseFactory;

final class OrderController extends Controller
{
    #[Route('/orders/{id}', methods: ['POST'])]
    public function process(LoggerInterface $logger, int $id): \Psr\Http\Message\ResponseInterface
    {
        $logger->info('Processing order', ['order_id' => $id]);

        try {
            // Process order...
            $logger->debug('Order validated successfully');

            return new ResponseFactory()->createJsonResponse(['status' => 'completed']);
        } catch (\Exception $e) {
            $logger->error('Order processing failed', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new ResponseFactory()->createJsonResponse(['error' => 'Failed'], 500);
        }
    }
}</code></pre>

    <h2>Multiple Channels</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Log\Monolog\MonologManager;

final class AlertController extends Controller
{
    #[Route('/send-alert', methods: ['POST'])]
    public function sendAlert(MonologManager $manager): ResponseInterface
    {
        // Log to specific channel
        $manager->channel('slack')->critical('Server down!');
        $manager->channel('daily')->info('Daily report generated');

        // Log to multiple channels at once
        $manager->stack(['daily', 'slack'])->alert('Security breach detected');

        return new ResponseFactory()->createJsonResponse(['alerted' => true]);
    }
}</code></pre>

    <h2>Contextual Logging</h2>
    <pre class="line-numbers"><code class="language-php">// Add context to all subsequent logs
$logger->withContext(['request_id' => $requestId, 'user_id' => $userId]);

$logger->info('Processing started');  // includes request_id and user_id
$logger->info('Step 1 complete');     // includes request_id and user_id
$logger->info('Processing complete'); // includes request_id and user_id</code></pre>

    <h2>Available Handlers</h2>
    <ul>
        <li><strong>StreamHandler</strong> - Log to files</li>
        <li><strong>RotatingFileHandler</strong> - Daily log rotation</li>
        <li><strong>SlackWebhookHandler</strong> - Send to Slack</li>
        <li><strong>SyslogHandler</strong> - System log</li>
        <li><strong>NativeMailerHandler</strong> - Send via email</li>
        <li><strong>RedisHandler</strong> - Log to Redis</li>
        <li><strong>ElasticsearchHandler</strong> - Log to Elasticsearch</li>
        <li>And 40+ more via Monolog ecosystem</li>
    </ul>

    <h2>Features</h2>
    <ul>
        <li><strong>PSR-3 compatible</strong> - Implements <code>LoggerInterface</code></li>
        <li><strong>Multiple channels</strong> - Log to different destinations</li>
        <li><strong>Stack channels</strong> - Combine multiple channels</li>
        <li><strong>Daily rotation</strong> - Automatic log file rotation</li>
        <li><strong>Formatters</strong> - JSON, Line, HTML formats</li>
        <li><strong>Processors</strong> - Add extra context automatically</li>
    </ul>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Why Monolog?</strong> While Larafony includes a built-in PSR-3 logger, Monolog offers 50+ handlers for different destinations, processors to enrich log data automatically, and industry-standard logging used by Symfony, Laravel, and many others.
    </div>
</x-bridges-layout>
