<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">WebSockets</h1>
    <p class="lead text-white-50">
        Real-time bidirectional communication with native PHP 8.5 Fibers engine and optional ReactPHP bridge for production scale
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-broadcast-pin me-2"></i>
        <strong>Modular Architecture:</strong> Core implementation uses PHP 8.5 Fibers with zero dependencies. For high-scale production, drop in the <code>larafony/websocket-react</code> bridge package.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's WebSocket system provides complete real-time communication capabilities:
    </p>
    <ul>
        <li><strong>RFC 6455 Compliant</strong> - Full WebSocket protocol with proper framing and handshakes</li>
        <li><strong>Zero Dependencies</strong> - Core uses only PHP 8.5 Fibers and ext-sockets</li>
        <li><strong>Swappable Engines</strong> - FiberEngine (core) or ReactEngine (bridge)</li>
        <li><strong>Event-Based</strong> - Simple <code>on('event', callback)</code> API</li>
        <li><strong>Broadcast Support</strong> - Send to all or filtered connections</li>
        <li><strong>Service Provider</strong> - Extend to register custom handlers</li>
    </ul>

    <h2>Quick Start</h2>

    <h3>1. Basic Server</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\WebSockets\Engine\FiberEngine;
use Larafony\Framework\WebSockets\Server;

$server = new Server(new FiberEngine(), '0.0.0.0', 8080);

$server->on('open', function ($data, $connection) {
    echo "Connected: {$connection->getId()}\n";
});

$server->on('message', function ($payload, $connection) {
    $connection->send("Echo: {$payload}");
});

$server->on('close', function ($data, $connection) {
    echo "Disconnected: {$connection->getId()}\n";
});

$server->run();</code></pre>

    <h3>2. Using Console Command</h3>

    <pre class="line-numbers"><code class="language-bash"># Start with default config
php bin/larafony websocket:start

# Custom host and port
php bin/larafony websocket:start --host=127.0.0.1 --port=9000</code></pre>

    <h3>3. Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// config/websocket.php
return [
    'host' => env('WEBSOCKET_HOST', '0.0.0.0'),
    'port' => (int) env('WEBSOCKET_PORT', 8080),
];</code></pre>

    <h2>Architecture</h2>

    <div class="alert-docs alert-success">
        <i class="bi bi-layers me-2"></i>
        <strong>Clean Separation:</strong> Protocol logic (Frame, Encoder, Decoder) is shared between engines. Only the I/O layer differs.
    </div>

    <pre class="line-numbers"><code class="language-text">┌─────────────────────────────────────────────────────┐
│               SHARED PROTOCOL LAYER                 │
├─────────────────────────────────────────────────────┤
│  Frame, Encoder, Decoder, Opcode, Handshake         │
│  Server, EventDispatcher, Connection logic          │
├─────────────────────────────────────────────────────┤
│           ENGINE ABSTRACTION (EngineContract)       │
├──────────────────────┬──────────────────────────────┤
│   FiberEngine        │      ReactEngine             │
│   (Core - no deps)   │   (Bridge - react/*)         │
└──────────────────────┴──────────────────────────────┘</code></pre>

    <h2>Custom Event Handling</h2>

    <p>
        The server automatically parses JSON messages with <code>event</code> field:
    </p>

    <pre class="line-numbers"><code class="language-javascript">// Client sends:
ws.send(JSON.stringify({
    event: 'chat_message',
    data: { message: 'Hello!' }
}));</code></pre>

    <pre class="line-numbers"><code class="language-php">// Server handles:
$server->on('chat_message', function ($data, $connection) {
    $message = $data['message'];
    // Process chat message...
});</code></pre>

    <h2>Broadcasting</h2>

    <pre class="line-numbers"><code class="language-php">// Broadcast to all connections
$server->broadcast('Hello everyone!');

// Broadcast with filter (exclude sender)
$server->broadcast(
    json_encode(['type' => 'notification', 'text' => 'New message']),
    fn($conn) => $conn->getId() !== $currentConnection->getId()
);</code></pre>

    <h2>Service Provider Pattern</h2>

    <p>
        Extend <code>WebSocketServiceProvider</code> to register your handlers:
    </p>

    <pre class="line-numbers"><code class="language-php">namespace App\Providers;

use Larafony\Framework\WebSockets\Contracts\ServerContract;
use Larafony\Framework\WebSockets\ServiceProviders\WebSocketServiceProvider;

class ChatWebSocketProvider extends WebSocketServiceProvider
{
    protected function registerDefaultHandlers(ServerContract $server): void
    {
        $server->on('chat_message', function ($data, $connection) {
            // Handle chat messages
        });

        $server->on('user_typing', function ($data, $connection) use ($server) {
            // Broadcast typing indicator
            $server->broadcast(
                json_encode(['event' => 'typing', 'user' => $data['userId']]),
                fn($c) => $c->getId() !== $connection->getId()
            );
        });
    }
}</code></pre>

    <h2>Bridge Package: ReactPHP</h2>

    <p>
        For production environments requiring high concurrency:
    </p>

    <pre class="line-numbers"><code class="language-bash">composer require larafony/websocket-react</code></pre>

    <pre class="line-numbers"><code class="language-php">// Use ReactWebSocketServiceProvider instead of WebSocketServiceProvider
use Larafony\WebSocket\ReactWebSocketServiceProvider;

class MyReactProvider extends ReactWebSocketServiceProvider
{
    protected function registerDefaultHandlers(ServerContract $server): void
    {
        // Same API as FiberEngine!
        $server->on('message', fn($data, $conn) => ...);
    }
}</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-speedometer2 me-2"></i>
        <strong>Performance:</strong> FiberEngine handles ~1000 concurrent connections. ReactEngine scales to 10,000+ with libuv/libev event loops.
    </div>

    <h2>Protocol Components</h2>

    <h3>Frame</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\WebSockets\Protocol\Frame;

// Create frames with factory methods
$textFrame = Frame::text('Hello');
$binaryFrame = Frame::binary($data);
$pingFrame = Frame::ping();
$closeFrame = Frame::close(1000, 'Normal closure');

// Send directly
$connection->send($textFrame);
$connection->send('Or just a string');</code></pre>

    <h3>Opcode Enum</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\WebSockets\Protocol\Opcode;

Opcode::TEXT;         // 1
Opcode::BINARY;       // 2
Opcode::CLOSE;        // 8
Opcode::PING;         // 9
Opcode::PONG;         // 10

$opcode->isControl(); // true for CLOSE, PING, PONG</code></pre>

    <h2>Client-Side (Vue.js Example)</h2>

    <pre class="line-numbers"><code class="language-javascript">const ws = new WebSocket('ws://localhost:8080');

ws.onopen = () => {
    console.log('Connected');
};

ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    if (data.event === 'ai_response') {
        displayMessage(data.data);
    }
};

const sendMessage = (message) => {
    ws.send(JSON.stringify({
        event: 'chat_message',
        data: { message }
    }));
};</code></pre>

    <h2>Practical Example: AI Chat in demo-app 🤖</h2>

    <p>Complete demonstration of WebSocket integration with AI - from Vue frontend through WebSocket to backend calling OpenAI API.</p>

    <h3>Controller (Inertia)</h3>
    <p><code>demo-app/src/Controllers/ChatAIController.php</code></p>

    <pre class="line-numbers"><code class="language-php">class ChatAIController extends Controller
{
    public function index(ConfigContract $config): \Inertia\Response
    {
        return inertia('Chat/Index', [
            'wsHost' => $config->get('websocket.host', 'localhost'),
            'wsPort' => $config->get('websocket.port', 8080),
        ]);
    }
}</code></pre>

    <h3>Message Listener with OpenAI</h3>
    <p><code>demo-app/src/Listeners/ChatMessageListener.php</code></p>

    <pre class="line-numbers"><code class="language-php">class ChatMessageListener
{
    public function __construct(
        private readonly ConfigContract $config,
    ) {}

    public function __invoke(array $data, ConnectionContract $connection): void
    {
        $message = $data['message'] ?? '';
        if (empty($message)) return;

        $response = $this->callOpenAI($message);

        $connection->send(Frame::text(json_encode([
            'event' => 'ai_response',
            'data' => ['message' => $response, 'timestamp' => time()],
        ])));
    }

    private function callOpenAI(string $message): string
    {
        $client = new CurlHttpClient();
        $apiKey = $this->config->get('openai.api_key');

        $request = new Request(
            'POST',
            new Uri('https://api.openai.com/v1/chat/completions'),
            ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$apiKey}"],
            json_encode([
                'model' => $this->config->get('openai.model', 'gpt-4'),
                'messages' => [['role' => 'user', 'content' => $message]],
            ])
        );

        $response = $client->sendRequest($request);
        $body = json_decode((string) $response->getBody(), true);

        return $body['choices'][0]['message']['content'] ?? 'Error';
    }
}</code></pre>

    <h3>ServiceProvider</h3>
    <p><code>demo-app/src/Providers/ChatWebSocketProvider.php</code></p>

    <pre class="line-numbers"><code class="language-php">class ChatWebSocketProvider extends WebSocketServiceProvider
{
    protected function registerDefaultHandlers(ServerContract $server): void
    {
        $listener = $this->container->get(ChatMessageListener::class);
        $server->on('chat_message', $listener);

        $server->on('open', fn($data, $conn) =>
            $conn->send(json_encode(['event' => 'welcome', 'data' => 'Connected']))
        );
    }
}</code></pre>

    <h3>Vue Component</h3>
    <p><code>demo-app/resources/js/Pages/Chat/Index.vue</code></p>

    <pre class="line-numbers"><code class="language-javascript">const ws = new WebSocket(`ws://${props.wsHost}:${props.wsPort}`);

ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    if (data.event === 'ai_response') {
        messages.value.push({
            type: 'ai',
            text: data.data.message,
            timestamp: data.data.timestamp,
        });
    }
};

function sendMessage() {
    ws.send(JSON.stringify({
        event: 'chat_message',
        data: { message: newMessage.value },
    }));
}</code></pre>

    <h3>Data Flow</h3>

    <div class="alert-docs alert-info">
        <ol class="mb-0">
            <li>User types message in Vue component</li>
            <li>Vue sends JSON via WebSocket: <code>{"event": "chat_message", "data": {"message": "..."}}</code></li>
            <li>Server dispatches <code>chat_message</code> event to ChatMessageListener</li>
            <li>Listener calls OpenAI API via PSR-18 CurlHttpClient</li>
            <li>AI response sent back via WebSocket</li>
            <li>Vue receives and displays response in real-time</li>
        </ol>
    </div>

    <p>The entire flow works <strong>without page reload</strong>, with instant response thanks to persistent WebSocket connection.</p>

    <h2>Framework Comparison 🔥</h2>

    <p>How does Larafony's WebSocket implementation compare to other PHP frameworks?</p>

    <h3>vs Laravel Reverb</h3>

    <p>Laravel introduced Reverb in 2024 as its first-party WebSocket solution:</p>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Aspect</th>
                <th>Laravel Reverb</th>
                <th>Larafony</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Dependencies</td>
                <td>Ratchet, Redis (for scaling)</td>
                <td><strong>Zero</strong> (core), ReactPHP (optional)</td>
            </tr>
            <tr>
                <td>Protocol</td>
                <td>Pusher protocol</td>
                <td><strong>Native RFC 6455</strong></td>
            </tr>
            <tr>
                <td>Architecture</td>
                <td>Separate Reverb server process</td>
                <td><strong>Integrated into framework</strong></td>
            </tr>
            <tr>
                <td>Scaling</td>
                <td>Requires Redis pub/sub</td>
                <td><strong>Built-in broadcast</strong></td>
            </tr>
            <tr>
                <td>Learning Curve</td>
                <td>Pusher concepts, channels, events</td>
                <td><strong>Simple <code>on('event', callback)</code></strong></td>
            </tr>
            <tr>
                <td>External Services</td>
                <td>Often paired with Pusher/Soketi</td>
                <td><strong>Fully self-contained</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="alert-docs alert-success">
        <i class="bi bi-trophy me-2"></i>
        <strong>Larafony advantage:</strong> No external services, no Pusher protocol abstraction, no Redis requirement. Just pure WebSockets with a clean, minimal API.
    </div>

    <h3>vs Symfony</h3>

    <p>Symfony does not include a built-in WebSocket solution:</p>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Aspect</th>
                <th>Symfony</th>
                <th>Larafony</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Native Support</td>
                <td>❌ None</td>
                <td><strong>✅ Full RFC 6455</strong></td>
            </tr>
            <tr>
                <td>Recommended Solution</td>
                <td>Mercure (SSE) or third-party Ratchet</td>
                <td><strong>Native FiberEngine or ReactEngine</strong></td>
            </tr>
            <tr>
                <td>Protocol</td>
                <td>Mercure uses Server-Sent Events</td>
                <td><strong>True bidirectional WebSockets</strong></td>
            </tr>
            <tr>
                <td>Integration</td>
                <td>Manual setup required</td>
                <td><strong>ServiceProvider + console command</strong></td>
            </tr>
            <tr>
                <td>Real-time</td>
                <td>One-way (SSE) or external package</td>
                <td><strong>True bidirectional</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="alert-docs alert-success">
        <i class="bi bi-trophy me-2"></i>
        <strong>Larafony advantage:</strong> First-class WebSocket support built from scratch, not delegated to external projects or limited to Server-Sent Events.
    </div>

    <h3>Why Larafony WebSockets Stand Out</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card bg-dark border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-seam text-primary me-2"></i>Zero Dependencies</h5>
                    <p class="card-text">Core uses only PHP 8.5 Fibers and ext-sockets. No composer packages for basic functionality.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-file-earmark-code text-primary me-2"></i>RFC 6455 From Scratch</h5>
                    <p class="card-text">Complete protocol implementation you can learn from and extend.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-arrow-left-right text-primary me-2"></i>Swappable Engines</h5>
                    <p class="card-text">FiberEngine for dev, ReactEngine for production - same API, same handlers.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-dark border-primary h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-lightning-charge text-primary me-2"></i>Simple Mental Model</h5>
                    <p class="card-text">No channels, no presence, no Pusher protocol. Just connections, events, broadcasts.</p>
                </div>
            </div>
        </div>
    </div>

    <pre class="line-numbers"><code class="language-php">// That's it. No Redis, no Pusher, no external services.
$server = new Server(new FiberEngine(), '0.0.0.0', 8080);
$server->on('message', fn($data, $conn) => $conn->send("Echo: $data"));
$server->run();</code></pre>

    <h2>Summary</h2>

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>Feature</th>
                <th>FiberEngine (Core)</th>
                <th>ReactEngine (Bridge)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Dependencies</td>
                <td>None (ext-sockets)</td>
                <td>react/event-loop, react/socket</td>
            </tr>
            <tr>
                <td>Concurrency Model</td>
                <td>PHP 8.5 Fibers</td>
                <td>Event Loop + Callbacks</td>
            </tr>
            <tr>
                <td>Scale</td>
                <td>~1,000 connections</td>
                <td>~10,000+ connections</td>
            </tr>
            <tr>
                <td>Best For</td>
                <td>Development, learning, simple apps</td>
                <td>Production, high-traffic</td>
            </tr>
        </tbody>
    </table>
</x-docs-layout>
