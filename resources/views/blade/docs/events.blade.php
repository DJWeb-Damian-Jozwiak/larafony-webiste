<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Event System (PSR-14)</h1>
    <p class="lead text-white-50">
        Powerful event-driven architecture based on PSR-14, enabling loosely coupled application components through publish-subscribe patterns
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-broadcast me-2"></i>
        <strong>Chapter 26:</strong> Part of the Larafony Framework - A modern PHP 8.5 framework built from scratch. Full implementation details with tests available at <a href="https://masterphp.eu" target="_blank" style="color: #6366f1;">masterphp.eu</a>
    </div>

    <h2>Overview</h2>
    <p>
        Chapter 26 introduces a powerful event-driven architecture based on <strong>PSR-14 (Event Dispatcher)</strong>, enabling loosely coupled application components through publish-subscribe patterns. This implementation provides attribute-based listener registration, automatic event type inference, priority-based execution, and stoppable event propagation — all while maintaining strict PSR compliance.
    </p>
    <p>
        The event system serves as the foundation for framework-wide observability, powering features like database query logging, cache monitoring, view rendering hooks, and route matching events. It eliminates tight coupling between components by allowing any part of the application to react to events without direct dependencies.
    </p>
    <p>
        Key features include automatic listener discovery using PHP 8.5 attributes (<code>#[Listen]</code>), priority-based listener execution (higher priority = earlier execution), container-based listener resolution for dependency injection, and framework events for application lifecycle (booting/booted), database operations (queries, transactions), cache operations (hit/miss/write/forget), view rendering (before/after), and route matching.
    </p>

    <h2>Key Components</h2>

    <h3>Event Dispatcher</h3>
    <ul>
        <li><strong>EventDispatcher</strong> - PSR-14 compliant event dispatcher with support for stoppable events (implements StoppableEventInterface), listener priority ordering (higher executes first), and sequential execution with early termination for stopped events</li>
        <li><strong>ListenerProvider</strong> - PSR-14 listener provider managing event-to-listener mappings with priority-based sorting (krsort for descending order), container-based listener resolution (automatic DI), and support for both callable and array-based listeners ([ClassName, 'method'])</li>
        <li><strong>ListenerDiscovery</strong> - Automatic listener registration via reflection scanning public methods for <code>#[Listen]</code> attributes, event type inference from method parameter types, and support for both class names and object instances</li>
    </ul>

    <h3>Attribute-Based Registration</h3>
    <ul>
        <li><strong>#[Listen]</strong> - Method attribute for marking event listeners with optional event class specification (auto-inferred from parameter type if not provided), configurable priority (default 0, higher = earlier execution), and repeatable attribute (single method can listen to multiple events)</li>
    </ul>

    <h3>Framework Events</h3>
    <p>The framework dispatches events at critical points in the application lifecycle:</p>

    <h4>Application Events</h4>
    <ul>
        <li><strong>ApplicationBooting</strong> - Dispatched before service providers boot</li>
        <li><strong>ApplicationBooted</strong> - Dispatched after all service providers have booted</li>
    </ul>

    <h4>Database Events</h4>
    <ul>
        <li><strong>QueryExecuted</strong> - Dispatched after each SQL query with query details (SQL, bindings, execution time, connection name) and backtrace for debugging</li>
        <li><strong>TransactionBeginning</strong> - Dispatched when database transaction starts</li>
        <li><strong>TransactionCommitted</strong> - Dispatched after successful transaction commit</li>
        <li><strong>TransactionRolledBack</strong> - Dispatched after transaction rollback</li>
    </ul>

    <h4>Cache Events</h4>
    <ul>
        <li><strong>CacheHit</strong> - Dispatched when cache key is found (includes key and value)</li>
        <li><strong>CacheMissed</strong> - Dispatched when cache key is not found (includes key only)</li>
        <li><strong>KeyWritten</strong> - Dispatched after cache write (includes key, value, TTL)</li>
        <li><strong>KeyForgotten</strong> - Dispatched after cache deletion (includes key)</li>
    </ul>

    <h4>View Events</h4>
    <ul>
        <li><strong>ViewRendering</strong> - Dispatched before view rendering (includes view name and data, allows modification)</li>
        <li><strong>ViewRendered</strong> - Dispatched after view rendering (includes view name, data, and rendered output)</li>
    </ul>

    <h4>Routing Events</h4>
    <ul>
        <li><strong>RouteMatched</strong> - Dispatched when route is matched to request (includes route name, URI pattern, controller, matched parameters)</li>
    </ul>

    <h3>Stoppable Events</h3>
    <ul>
        <li><strong>StoppableEvent</strong> - Abstract base class implementing PSR-14 StoppableEventInterface with <code>stopPropagation()</code> method to halt listener execution and <code>isPropagationStopped()</code> to check stopped state</li>
    </ul>

    <h2>PSR Standards Implemented</h2>
    <ul>
        <li><strong>PSR-14</strong>: Event Dispatcher - Full implementation with EventDispatcher (dispatches events to listeners), ListenerProvider (provides listeners for events), and StoppableEventInterface (allows stopping propagation)</li>
        <li><strong>PSR-11</strong>: Container Interface - Used for automatic listener instantiation and dependency injection</li>
    </ul>

    <h2>New Attributes</h2>

    <h3>#[Listen]</h3>
    <p>Marks a method as an event listener with optional event class and priority configuration.</p>

    <p><strong>Parameters:</strong></p>
    <ul>
        <li><code>event</code> (class-string|null) - Event class name. If null, inferred from first method parameter type</li>
        <li><code>priority</code> (int) - Listener priority. Higher values execute first. Default: 0</li>
    </ul>

    <p><strong>Target:</strong> Methods only (Attribute::TARGET_METHOD)</p>
    <p><strong>Repeatable:</strong> Yes (Attribute::IS_REPEATABLE) - one method can listen to multiple events</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Events\Attributes\Listen;
use Larafony\Framework\Events\Database\QueryExecuted;
use Larafony\Framework\Events\Cache\CacheHit;

class MyListener
{
    // Explicit event class
    #[Listen(event: QueryExecuted::class, priority: 10)]
    public function onQuery(QueryExecuted $event): void
    {
        // High priority (10) - executes before priority 0
    }

    // Auto-inferred from parameter type
    #[Listen]
    public function onCacheHit(CacheHit $event): void
    {
        // Event type inferred from parameter
    }

    // Multiple listeners on same method
    #[Listen(event: CacheHit::class)]
    #[Listen(event: CacheMissed::class)]
    public function onCacheAccess(object $event): void
    {
        // Handles both CacheHit and CacheMissed
    }
}</code></pre>

    <h2>Usage Examples</h2>

    <h3>Basic Event Listening</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Events\EventDispatcher;
use Larafony\Framework\Events\ListenerProvider;
use Larafony\Framework\Events\Database\QueryExecuted;

// Manual listener registration
$provider = new ListenerProvider();
$dispatcher = new EventDispatcher($provider);

// Register listener with priority
$provider->listen(
    QueryExecuted::class,
    function (QueryExecuted $event) {
        echo "Query: {$event->sql}\n";
        echo "Time: {$event->time}ms\n";
    },
    priority: 5
);

// Dispatch event
$event = new QueryExecuted(
    sql: 'SELECT * FROM users WHERE id = ?',
    rawSql: 'SELECT * FROM users WHERE id = 1',
    bindings: [1],
    time: 2.45,
    connection: 'mysql'
);

$dispatcher->dispatch($event);</code></pre>

    <h3>Attribute-Based Listeners</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Events\Attributes\Listen;
use Larafony\Framework\Events\Database\QueryExecuted;
use Larafony\Framework\Events\Cache\CacheHit;
use Larafony\Framework\Events\Cache\CacheMissed;

class ApplicationMonitor
{
    // High priority query logging
    #[Listen(priority: 100)]
    public function logSlowQueries(QueryExecuted $event): void
    {
        if ($event->time > 100) {
            // Log slow queries (>100ms)
            error_log("SLOW QUERY: {$event->sql} ({$event->time}ms)");
        }
    }

    // Cache monitoring
    #[Listen]
    public function trackCacheHitRate(CacheHit $event): void
    {
        // Increment cache hit counter
        $this->incrementMetric('cache.hits');
    }

    #[Listen]
    public function trackCacheMissRate(CacheMissed $event): void
    {
        // Increment cache miss counter
        $this->incrementMetric('cache.misses');
    }

    // Multiple events, one handler
    #[Listen(event: CacheHit::class)]
    #[Listen(event: CacheMissed::class)]
    public function logCacheAccess(object $event): void
    {
        $type = $event instanceof CacheHit ? 'HIT' : 'MISS';
        echo "Cache {$type}: {$event->key}\n";
    }

    private function incrementMetric(string $name): void
    {
        // Implementation...
    }
}</code></pre>

    <h3>Stoppable Events</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Events\StoppableEvent;
use Larafony\Framework\Events\Attributes\Listen;

// Custom stoppable event
class UserRegistering extends StoppableEvent
{
    public function __construct(
        public string $email,
        public string $password,
        public ?string $reason = null
    ) {
    }
}

class RegistrationValidator
{
    #[Listen(priority: 100)]
    public function validateEmail(UserRegistering $event): void
    {
        if (!filter_var($event->email, FILTER_VALIDATE_EMAIL)) {
            $event->reason = 'Invalid email format';
            $event->stopPropagation(); // Stop further listeners
        }
    }

    #[Listen(priority: 50)]
    public function checkBlacklist(UserRegistering $event): void
    {
        if ($this->isBlacklisted($event->email)) {
            $event->reason = 'Email is blacklisted';
            $event->stopPropagation();
        }
    }

    #[Listen(priority: 0)]
    public function createUser(UserRegistering $event): void
    {
        // Only executes if not stopped
        echo "Creating user: {$event->email}\n";
    }

    private function isBlacklisted(string $email): bool
    {
        return false;
    }
}

// Usage
$event = new UserRegistering('spam@example.com', 'password');
$dispatcher->dispatch($event);

if ($event->isPropagationStopped()) {
    echo "Registration failed: {$event->reason}\n";
}</code></pre>

    <h2>Testing</h2>
    <p>The event system is covered by comprehensive test suites:</p>

    <h3>EventDispatcherTest</h3>
    <p><strong>Location:</strong> <code>tests/Larafony/Events/EventDispatcherTest.php</code></p>
    <p><strong>Coverage:</strong> 6 tests covering:</p>
    <ul>
        <li>Basic event dispatching</li>
        <li>Multiple listeners on same event</li>
        <li>Priority-based execution order</li>
        <li>Stoppable event propagation</li>
        <li>Event modification by listeners</li>
        <li>Empty listener handling</li>
    </ul>
    <p><strong>All tests pass:</strong> ✅ 6/6 tests</p>

    <h3>ListenerProviderTest</h3>
    <p><strong>Location:</strong> <code>tests/Larafony/Events/ListenerProviderTest.php</code></p>
    <p><strong>Coverage:</strong> 8 tests covering listener provider functionality</p>
    <p><strong>All tests pass:</strong> ✅ 8/8 tests</p>

    <h3>ListenerDiscoveryTest</h3>
    <p><strong>Location:</strong> <code>tests/Larafony/Events/ListenerDiscoveryTest.php</code></p>
    <p><strong>Coverage:</strong> 7 tests covering listener discovery functionality</p>
    <p><strong>All tests pass:</strong> ✅ 7/7 tests</p>

    <p><strong>Total Test Coverage:</strong></p>
    <ul>
        <li>21 tests across 3 test suites</li>
        <li>40+ assertions</li>
        <li>100% pass rate</li>
        <li>Covers all PSR-14 functionality</li>
    </ul>

    <div class="alert-docs alert-success">
        <i class="bi bi-book me-2"></i>
        <strong>Learn More:</strong> This implementation is explained in detail with step-by-step tutorials, tests, and best practices at <a href="https://masterphp.eu" target="_blank" style="color: #22c55e;">masterphp.eu</a>
    </div>
</x-docs-layout>
