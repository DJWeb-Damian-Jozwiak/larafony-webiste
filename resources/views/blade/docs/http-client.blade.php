<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">HTTP Client (PSR-18)</h1>
    <p class="lead text-white-50">
        Make HTTP requests to external APIs with Larafony's PSR-18 compliant HTTP client.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>PSR-18 Compliant:</strong> Fully implements <code>Psr\Http\Client\ClientInterface</code> for maximum interoperability.
    </div>

    <h2>Overview</h2>
    <p>
        The HTTP Client provides a clean API for making outbound HTTP requests:
    </p>
    <ul>
        <li><strong>PSR-18 Compliant</strong> - Standard HTTP client interface</li>
        <li><strong>Native CurlHandle</strong> - No external dependencies</li>
        <li><strong>Configurable</strong> - Timeouts, SSL, proxy, redirects</li>
        <li><strong>Testable</strong> - MockHttpClient for testing</li>
    </ul>

    <h2>Basic Usage</h2>

    <h3>GET Request</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Client\CurlHttpClient;
use Larafony\Framework\Http\Factories\RequestFactory;

$client = new CurlHttpClient();
$requestFactory = new RequestFactory();

// Create GET request
$request = $requestFactory->createRequest('GET', 'https://api.github.com/users/octocat');

// Send request
$response = $client->sendRequest($request);

// Get response data
echo $response->getStatusCode(); // 200
echo $response->getBody();       // JSON response</code></pre>

    <h3>POST Request with JSON</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Factories\StreamFactory;

$streamFactory = new StreamFactory();

// Create POST request
$request = $requestFactory->createRequest('POST', 'https://api.example.com/users')
    ->withHeader('Content-Type', 'application/json')
    ->withBody($streamFactory->createStream(json_encode([
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ])));

$response = $client->sendRequest($request);
$data = json_decode($response->getBody(), true);</code></pre>

    <h2>Configuration</h2>

    <h3>Client Configuration</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Client\Config\HttpClientConfig;

// Custom configuration
$config = new HttpClientConfig(
    timeout: 30,                // Request timeout in seconds
    followRedirects: true,      // Follow HTTP redirects
    maxRedirects: 5,            // Maximum redirects to follow
    verifyPeer: true,           // Verify SSL certificate
    verifyHost: true,           // Verify SSL host
);

$client = new CurlHttpClient($config);</code></pre>

    <h3>Convenience Factory Methods</h3>

    <pre class="line-numbers"><code class="language-php">// Quick configurations
$config = HttpClientConfig::withTimeout(60);
$config = HttpClientConfig::insecure();  // Disable SSL verification (dev only!)
$config = HttpClientConfig::withProxy('proxy.local:8080', 'user:pass');</code></pre>

    <h2>Making Requests</h2>

    <h3>Different HTTP Methods</h3>

    <pre class="line-numbers"><code class="language-php">// GET
$request = $requestFactory->createRequest('GET', 'https://api.example.com/users');

// POST
$request = $requestFactory->createRequest('POST', 'https://api.example.com/users');

// PUT
$request = $requestFactory->createRequest('PUT', 'https://api.example.com/users/1');

// PATCH
$request = $requestFactory->createRequest('PATCH', 'https://api.example.com/users/1');

// DELETE
$request = $requestFactory->createRequest('DELETE', 'https://api.example.com/users/1');</code></pre>

    <h3>Adding Headers</h3>

    <pre class="line-numbers"><code class="language-php">$request = $requestFactory->createRequest('GET', 'https://api.example.com/data')
    ->withHeader('Authorization', 'Bearer ' . $token)
    ->withHeader('Accept', 'application/json')
    ->withHeader('User-Agent', 'LarafonyApp/1.0');</code></pre>

    <h3>Query Parameters</h3>

    <pre class="line-numbers"><code class="language-php">// Build URL with query parameters
$url = 'https://api.example.com/search?' . http_build_query([
    'q' => 'larafony',
    'page' => 1,
    'per_page' => 20
]);

$request = $requestFactory->createRequest('GET', $url);</code></pre>

    <h2>Response Handling</h2>

    <h3>Reading Response</h3>

    <pre class="line-numbers"><code class="language-php">$response = $client->sendRequest($request);

// Status code
$statusCode = $response->getStatusCode(); // 200

// Headers
$contentType = $response->getHeaderLine('Content-Type');
$allHeaders = $response->getHeaders();

// Body
$body = $response->getBody()->getContents();

// JSON response
$data = json_decode($body, true);</code></pre>

    <h3>Checking Status</h3>

    <pre class="line-numbers"><code class="language-php">if ($response->getStatusCode() === 200) {
    // Success
}

if ($response->getStatusCode() >= 400) {
    // Error
}</code></pre>

    <h2>Error Handling</h2>

    <h3>Network Errors</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Client\Exceptions\ClientError;
use Larafony\Framework\Http\Client\Exceptions\ConnectionError;
use Larafony\Framework\Http\Client\Exceptions\TimeoutError;

try {
    $response = $client->sendRequest($request);
} catch (TimeoutError $e) {
    // Request timed out
    echo "Request timed out";
} catch (ConnectionError $e) {
    // Connection failed
    echo "Connection failed: " . $e->getMessage();
} catch (ClientError $e) {
    // Other client errors
    echo "Client error: " . $e->getMessage();
}</code></pre>

    <h3>HTTP Status Errors</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Client\Exceptions\NotFoundError;
use Larafony\Framework\Http\Client\Exceptions\UnauthorizedError;

try {
    $response = $client->sendRequest($request);
} catch (UnauthorizedError $e) {
    // 401 Unauthorized
    echo "Authentication required";
} catch (NotFoundError $e) {
    // 404 Not Found
    echo "Resource not found";
}</code></pre>

    <h2>Testing with MockHttpClient</h2>

    <h3>Creating Mock Responses</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Client\MockHttpClient;
use Larafony\Framework\Http\Factories\ResponseFactory;

// Create mock response
$mockResponse = (new ResponseFactory())
    ->createResponse(200)
    ->withHeader('Content-Type', 'application/json')
    ->withJson(['id' => 1, 'name' => 'Test User']);

// Create mock client
$client = new MockHttpClient($mockResponse);

// Use in tests - no actual HTTP request is made
$response = $client->sendRequest($anyRequest);
// Always returns the mocked response</code></pre>

    <h3>Testing Service with HTTP Client</h3>

    <pre class="line-numbers"><code class="language-php">class GitHubService
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactory $requestFactory
    ) {}

    public function getUser(string $username): array
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            "https://api.github.com/users/{$username}"
        );

        $response = $this->httpClient->sendRequest($request);
        return json_decode($response->getBody(), true);
    }
}

// In tests
$mockResponse = (new ResponseFactory())
    ->createResponse(200)
    ->withJson(['login' => 'octocat', 'name' => 'The Octocat']);

$mockClient = new MockHttpClient($mockResponse);
$service = new GitHubService($mockClient, new RequestFactory());

$user = $service->getUser('octocat'); // Uses mock, no network call
assert($user['login'] === 'octocat');</code></pre>

    <h2>Practical Examples</h2>

    <h3>Example 1: API Client</h3>

    <pre class="line-numbers"><code class="language-php">class WeatherApiClient
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $apiKey
    ) {}

    public function getCurrentWeather(string $city): array
    {
        $url = 'https://api.weather.com/current?' . http_build_query([
            'city' => $city,
            'apikey' => $this->apiKey
        ]);

        $request = (new RequestFactory())
            ->createRequest('GET', $url)
            ->withHeader('Accept', 'application/json');

        try {
            $response = $this->httpClient->sendRequest($request);
            return json_decode($response->getBody(), true);
        } catch (ClientError $e) {
            throw new WeatherApiException('Failed to fetch weather', 0, $e);
        }
    }
}</code></pre>

    <h3>Example 2: Webhook Sender</h3>

    <pre class="line-numbers"><code class="language-php">class WebhookService
{
    public function __construct(
        private readonly ClientInterface $httpClient
    ) {}

    public function sendWebhook(string $url, array $data): bool
    {
        $request = (new RequestFactory())
            ->createRequest('POST', $url)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(
                (new StreamFactory())->createStream(json_encode($data))
            );

        try {
            $response = $this->httpClient->sendRequest($request);
            return $response->getStatusCode() === 200;
        } catch (ClientError $e) {
            Log::error('Webhook failed', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Use dependency injection for HTTP client</li>
            <li>Set appropriate timeouts for external APIs</li>
            <li>Handle network errors gracefully</li>
            <li>Use MockHttpClient in tests</li>
            <li>Add retry logic for transient failures</li>
            <li>Log failed requests for debugging</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't disable SSL verification in production</li>
            <li>Don't ignore network errors</li>
            <li>Don't use infinite timeouts</li>
            <li>Don't make HTTP calls in loops without rate limiting</li>
        </ul>
    </div>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-journal-code me-2 text-primary"></i>Logging</h4>
                <p class="text-white-50 mb-3">Log HTTP requests and responses for debugging.</p>
                <a href="/docs/logging" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-gear me-2 text-primary"></i>Configuration</h4>
                <p class="text-white-50 mb-3">Store API keys and endpoints in configuration.</p>
                <a href="/docs/config" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
