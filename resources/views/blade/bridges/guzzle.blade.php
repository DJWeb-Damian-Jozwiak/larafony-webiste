<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Guzzle HTTP Bridge</h1>
    <p class="lead text-white-50">
        Integrate Guzzle HTTP client with Larafony for async requests, middleware, and PSR-18 compatibility.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/http-guzzle</code></pre>

    <h2>Configuration</h2>
    <p>Register the service provider in your <code>bootstrap.php</code>:</p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Http\Guzzle\ServiceProviders\GuzzleServiceProvider;

$app->withServiceProviders([
    GuzzleServiceProvider::class
]);</code></pre>

    <h2>Basic Usage</h2>
    <p>Inject <code>GuzzleHttpClient</code> into your controllers via dependency injection:</p>
    <pre class="line-numbers"><code class="language-php">use Larafony\Http\Guzzle\GuzzleHttpClient;
use Larafony\Framework\Web\Controller;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Http\Factories\ResponseFactory;
use Psr\Http\Message\ResponseInterface;

final class ApiController extends Controller
{
    #[Route('/fetch-users', methods: ['GET'])]
    public function fetchUsers(GuzzleHttpClient $client): ResponseInterface
    {
        // Simple GET request
        $response = $client->getGuzzle()->get('https://api.example.com/users');
        $data = json_decode($response->getBody()->getContents(), true);

        return new ResponseFactory()->createJsonResponse($data);
    }

    #[Route('/create-user', methods: ['POST'])]
    public function createUser(GuzzleHttpClient $client, UserDto $dto): ResponseInterface
    {
        // POST with JSON body
        $response = $client->post('https://api.example.com/users', [
            'json' => $dto->toArray(),
        ]);

        return new ResponseFactory()->createJsonResponse([
            'status' => 'created',
            'data' => json_decode($response->getBody()->getContents(), true),
        ], 201);
    }
}</code></pre>

    <h2>Bearer Token Authentication</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Http\Guzzle\GuzzleHttpClientFactory;
use Larafony\Framework\Web\Config;

#[Route('/authorized-request', methods: ['GET'])]
public function authorized(): ResponseInterface
{
    $token = Config::get('api.token');
    $client = GuzzleHttpClientFactory::withBearerToken($token);

    $response = $client->get('https://api.example.com/protected');

    return new ResponseFactory()->createJsonResponse(
        json_decode($response->getBody()->getContents(), true)
    );
}</code></pre>

    <h2>Async Requests</h2>
    <p>Make concurrent requests for better performance:</p>
    <pre class="line-numbers"><code class="language-php">use GuzzleHttp\Promise\Utils;

#[Route('/dashboard-data', methods: ['GET'])]
public function dashboardData(GuzzleHttpClient $client): ResponseInterface
{
    // Fire all requests concurrently
    $promises = [
        'users' => $client->getGuzzle()->getAsync('https://api.example.com/users'),
        'orders' => $client->getGuzzle()->getAsync('https://api.example.com/orders'),
        'stats' => $client->getGuzzle()->getAsync('https://api.example.com/stats'),
    ];

    // Wait for all to complete
    $results = Utils::unwrap($promises);

    return new ResponseFactory()->createJsonResponse([
        'users' => json_decode($results['users']->getBody()->getContents(), true),
        'orders' => json_decode($results['orders']->getBody()->getContents(), true),
        'stats' => json_decode($results['stats']->getBody()->getContents(), true),
    ]);
}</code></pre>

    <h2>File Uploads</h2>
    <pre class="line-numbers"><code class="language-php">#[Route('/upload', methods: ['POST'])]
public function upload(GuzzleHttpClient $client): ResponseInterface
{
    $response = $client->post('https://api.example.com/upload', [
        'multipart' => [
            [
                'name' => 'file',
                'contents' => fopen('/path/to/file.pdf', 'r'),
                'filename' => 'document.pdf',
            ],
            [
                'name' => 'description',
                'contents' => 'Important document',
            ],
        ],
    ]);

    return new ResponseFactory()->createJsonResponse(['uploaded' => true]);
}</code></pre>

    <h2>PSR-18 Compatibility</h2>
    <p>The bridge implements PSR-18 <code>ClientInterface</code>, making it interchangeable with any PSR-18 client:</p>
    <pre class="line-numbers"><code class="language-php">use Psr\Http\Client\ClientInterface;

// Works with any PSR-18 compatible code
function fetchData(ClientInterface $client, RequestInterface $request): array
{
    $response = $client->sendRequest($request);
    return json_decode($response->getBody()->getContents(), true);
}</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>PSR-18 compatible</strong> - Implements <code>ClientInterface</code></li>
        <li><strong>Full Guzzle API</strong> - Access all Guzzle features</li>
        <li><strong>Async requests</strong> - Concurrent HTTP requests with promises</li>
        <li><strong>Middleware system</strong> - Retry, logging, caching</li>
        <li><strong>File uploads</strong> - Multipart form data support</li>
        <li><strong>Streaming</strong> - Handle large responses efficiently</li>
    </ul>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Why Guzzle?</strong> While Larafony includes a built-in PSR-18 HTTP client, Guzzle offers async/concurrent requests, a rich middleware ecosystem, automatic retries, and cookie jar management.
    </div>
</x-bridges-layout>
