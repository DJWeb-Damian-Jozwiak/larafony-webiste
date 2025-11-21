<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">DebugBar & Model Eager Loading</h1>
    <p class="lead text-white-50">
        Professional DebugBar for real-time application insights and N+1 query prevention through model eager loading
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-bug me-2"></i>
        <strong>Chapter 27:</strong> Part of the Larafony Framework - A modern PHP 8.5 framework built from scratch. Full implementation details with tests available at <a href="https://masterphp.eu" target="_blank" style="color: #6366f1;">masterphp.eu</a>
    </div>

    <h2>Overview</h2>
    <p>
        Chapter 27 introduces two critical development features: a <strong>professional DebugBar</strong> for real-time application insights and <strong>N+1 query prevention</strong> through model eager loading. The DebugBar provides comprehensive debugging information during development, while eager loading ensures production-grade performance by eliminating the notorious N+1 query problem.
    </p>
    <p>
        The DebugBar is a non-intrusive toolbar injected into HTML responses, collecting data through event listeners without modifying application logic. It tracks database queries with execution time and backtrace, cache operations (hits/misses/writes/deletes) with hit ratio calculation, view rendering with template names and data, route matching with parameters and controller info, request/response details (method, URI, headers, status), application performance (execution time, memory usage, peak memory), and timeline visualization showing the complete request lifecycle.
    </p>
    <p>
        Model eager loading solves the N+1 query problem by loading related models in bulk rather than one-by-one. Instead of executing 1 + N queries (one to fetch parent models, then one query per parent for its relations), eager loading executes just 2 queries (one for parents, one for all related models), dramatically reducing database load and improving response times.
    </p>

    <div class="alert-docs alert-warning">
        <i class="bi bi-lightning-charge-fill me-2"></i>
        <strong>Key Performance Impact:</strong>
        <ul class="mb-0 mt-2">
            <li><strong>Without Eager Loading:</strong> 101 queries for 100 users with roles (1 + 100)</li>
            <li><strong>With Eager Loading:</strong> 2 queries for 100 users with roles (1 + 1)</li>
            <li><strong>Reduction:</strong> 98% fewer queries</li>
        </ul>
    </div>

    <h2>Key Components</h2>

    <h3>DebugBar System</h3>
    <ul>
        <li><strong>DebugBar</strong> - Central orchestrator managing data collectors with <code>addCollector()</code> for registration, <code>collect()</code> for gathering data from all collectors, <code>enable()/disable()</code> for toggling, and <code>isEnabled()</code> for status checking</li>
        <li><strong>DataCollectorContract</strong> - Interface for collectors with <code>collect(): array</code> to gather data and <code>getName(): string</code> for identification</li>
        <li><strong>InjectDebugBar Middleware</strong> - PSR-15 middleware injecting toolbar into HTML responses by checking Content-Type (must be text/html), verifying status code (only 2xx/3xx, not errors), rendering toolbar view, and inserting before <code>&lt;/body&gt;</code> tag</li>
    </ul>

    <h3>Data Collectors</h3>
    <p>Each collector implements DataCollectorContract and listens to framework events:</p>
    <ul>
        <li><strong>QueryCollector</strong> - Listens to QueryExecuted events, tracks all SQL queries with execution time, bindings, connection name, and backtrace (filtered to exclude framework internals), calculates total query time and count</li>
        <li><strong>CacheCollector</strong> - Listens to CacheHit, CacheMissed, KeyWritten, KeyForgotten events, tracks operations with timestamps, calculates hit ratio percentage, monitors total cache size (bytes written)</li>
        <li><strong>ViewCollector</strong> - Listens to ViewRendered events, tracks rendered views with names and data, measures rendering time per view</li>
        <li><strong>RouteCollector</strong> - Listens to RouteMatched events, captures route name, URI pattern, HTTP method, controller/action, and matched parameters</li>
        <li><strong>RequestCollector</strong> - Captures request details including HTTP method, URI, headers, query parameters, and request body</li>
        <li><strong>PerformanceCollector</strong> - Measures execution time from REQUEST_TIME_FLOAT, tracks memory usage (current, peak, delta), formats bytes to human-readable units (B, KB, MB, GB)</li>
        <li><strong>TimelineCollector</strong> - Creates visual timeline by listening to ApplicationBooting, ApplicationBooted, RouteMatched, QueryExecuted, ViewRendering, ViewRendered events, tracks event start/end times, calculates durations in milliseconds, and sorts chronologically</li>
    </ul>

    <h3>Model Eager Loading</h3>
    <ul>
        <li><strong>ModelQueryBuilder</strong> - Enhanced with <code>with(array $relations)</code> method for specifying relations to eager load, supports nested relations via dot notation (e.g., 'user.profile.avatar'), stores eager load configuration in <code>$eagerLoad</code> array</li>
        <li><strong>EagerRelationsLoader</strong> - Orchestrates eager loading by iterating through configured relations, delegating to appropriate relation loader, and passing nested relation configuration</li>
        <li><strong>RelationLoaderContract</strong> - Interface for relation loaders with <code>load(array $models, string $relationName, RelationContract $relation, array $nested): void</code></li>
        <li><strong>BelongsToLoader</strong> - Loads belongsTo relations by collecting foreign key values from parent models, executing single whereIn query to fetch all related models, indexing by primary key for O(1) lookup, and assigning to parent models</li>
        <li><strong>HasManyLoader</strong> - Loads hasMany relations by collecting local key values, executing single whereIn query, grouping results by foreign key, and assigning arrays to parent models</li>
        <li><strong>BelongsToManyLoader</strong> - Loads belongsToMany relations via pivot tables by collecting parent IDs, querying pivot table, fetching related models, and grouping by parent ID</li>
        <li><strong>HasManyThroughLoader</strong> - Loads hasManyThrough relations by traversing intermediate models, executing optimized join query, and grouping results</li>
    </ul>

    <h2>Usage Examples</h2>

    <h3>DebugBar Integration</h3>
    <p>The DebugBar is automatically enabled in development environments and displays at the bottom of HTML pages:</p>

    <pre class="line-numbers"><code class="language-php">// config/app.php - DebugBar is registered via DebugBarServiceProvider
use Larafony\Framework\DebugBar\ServiceProviders\DebugBarServiceProvider;

return [
    'providers' => [
        // ... other providers
        DebugBarServiceProvider::class,
    ],
];

// config/debugbar.php - Configure DebugBar behavior
use Larafony\Framework\Config\Environment\EnvReader;
use Larafony\Framework\DebugBar\Collectors\CacheCollector;
use Larafony\Framework\DebugBar\Collectors\PerformanceCollector;
use Larafony\Framework\DebugBar\Collectors\QueryCollector;
use Larafony\Framework\DebugBar\Collectors\RequestCollector;
use Larafony\Framework\DebugBar\Collectors\RouteCollector;
use Larafony\Framework\DebugBar\Collectors\TimelineCollector;
use Larafony\Framework\DebugBar\Collectors\ViewCollector;

return [
    'enabled' => EnvReader::read('APP_DEBUG', false),

    'collectors' => [
        'queries' => QueryCollector::class,
        'cache' => CacheCollector::class,
        'views' => ViewCollector::class,
        'route' => RouteCollector::class,
        'request' => RequestCollector::class,
        'performance' => PerformanceCollector::class,
        'timeline' => TimelineCollector::class,
    ]
];

// The middleware is automatically registered in HTTP kernel
// No manual configuration needed!</code></pre>

    <p><strong>What You See:</strong></p>
    <p>When you load any HTML page in development, the DebugBar appears at the bottom showing:</p>
    <ul>
        <li><strong>Queries Tab:</strong> All executed queries with syntax-highlighted SQL, execution time, backtrace to source, and bindings</li>
        <li><strong>Cache Tab:</strong> Cache operations with hit/miss ratio, total operations, and size metrics</li>
        <li><strong>Views Tab:</strong> Rendered templates with data passed to each view</li>
        <li><strong>Route Tab:</strong> Matched route details with parameters</li>
        <li><strong>Request Tab:</strong> Full request information (method, URI, headers, body)</li>
        <li><strong>Performance Tab:</strong> Execution time, memory usage, peak memory</li>
        <li><strong>Timeline Tab:</strong> Visual waterfall chart of application lifecycle</li>
    </ul>

    <h3>Basic Eager Loading</h3>

    <pre class="line-numbers"><code class="language-php">use App\Models\User;

// ❌ N+1 Problem (101 queries for 100 users)
$users = User::query()->get(); // 1 query

foreach ($users as $user) {
    echo $user->role->name; // 100 queries (one per user)
}
// Total: 101 queries

// ✅ With Eager Loading (2 queries for 100 users)
$users = User::query()->with(['role'])->get(); // 2 queries (users + roles)

foreach ($users as $user) {
    echo $user->role->name; // No query - already loaded
}
// Total: 2 queries</code></pre>

    <p><strong>DebugBar Shows:</strong></p>
    <ul>
        <li>Without eager loading: 101 queries, ~150ms total time</li>
        <li>With eager loading: 2 queries, ~3ms total time</li>
        <li><strong>Performance improvement:</strong> 50x faster</li>
    </ul>

    <h3>Nested Eager Loading</h3>

    <pre class="line-numbers"><code class="language-php">use App\Models\Post;

// Load posts with author and author's profile
$posts = Post::query()
    ->with(['author.profile'])
    ->get();

// 3 queries total:
// 1. SELECT * FROM posts
// 2. SELECT * FROM users WHERE id IN (...)
// 3. SELECT * FROM profiles WHERE user_id IN (...)

foreach ($posts as $post) {
    echo $post->author->profile->bio; // No queries - all loaded
}</code></pre>

    <p><strong>Nested Relation Syntax:</strong></p>
    <ul>
        <li><code>'author'</code> - Load author relation</li>
        <li><code>'author.profile'</code> - Load author AND author's profile</li>
        <li><code>'author.profile.avatar'</code> - Load author, profile, and avatar (3 levels deep)</li>
    </ul>

    <h3>Multiple Relations</h3>

    <pre class="line-numbers"><code class="language-php">use App\Models\User;

// Load multiple relations at once
$users = User::query()
    ->with(['role', 'permissions', 'posts'])
    ->get();

// 4 queries total:
// 1. SELECT * FROM users
// 2. SELECT * FROM roles WHERE id IN (...)
// 3. SELECT * FROM permissions WHERE user_id IN (...)
// 4. SELECT * FROM posts WHERE author_id IN (...)

foreach ($users as $user) {
    echo $user->role->name;
    echo count($user->permissions);
    echo count($user->posts);
    // All data already loaded - no additional queries
}</code></pre>

    <h3>Complex Nested Loading</h3>

    <pre class="line-numbers"><code class="language-php">use App\Models\Category;

// Deep nesting with multiple branches
$categories = Category::query()
    ->with([
        'posts.author.profile',      // Posts -> Authors -> Profiles
        'posts.comments.user',        // Posts -> Comments -> Users
        'posts.tags',                 // Posts -> Tags
    ])
    ->get();

// 7 queries total:
// 1. SELECT * FROM categories
// 2. SELECT * FROM posts WHERE category_id IN (...)
// 3. SELECT * FROM users WHERE id IN (...)  -- authors
// 4. SELECT * FROM profiles WHERE user_id IN (...)
// 5. SELECT * FROM comments WHERE post_id IN (...)
// 6. SELECT * FROM users WHERE id IN (...)  -- comment authors
// 7. SELECT * FROM tags JOIN post_tag WHERE post_id IN (...)

foreach ($categories as $category) {
    foreach ($category->posts as $post) {
        echo $post->author->profile->bio;
        foreach ($post->comments as $comment) {
            echo $comment->user->name;
        }
        foreach ($post->tags as $tag) {
            echo $tag->name;
        }
    }
}
// All data accessed without additional queries!</code></pre>

    <p><strong>DebugBar Timeline Shows:</strong></p>
    <ul>
        <li>Query 1: Categories (2ms)</li>
        <li>Query 2: Posts (3ms)</li>
        <li>Query 3: Authors (2ms)</li>
        <li>Query 4: Profiles (1ms)</li>
        <li>Query 5: Comments (4ms)</li>
        <li>Query 6: Comment Users (2ms)</li>
        <li>Query 7: Tags (3ms)</li>
        <li><strong>Total: 17ms for complete dataset</strong></li>
    </ul>

    <h2>Implementation Details</h2>

    <h3>DebugBar</h3>
    <p><strong>Location:</strong> <code>src/Larafony/DebugBar/DebugBar.php</code></p>
    <p><strong>Purpose:</strong> Central orchestrator managing all data collectors and coordinating data collection.</p>
    <p><strong>Key Methods:</strong></p>
    <ul>
        <li><code>addCollector(string $name, DataCollectorContract $collector): void</code> - Register a collector</li>
        <li><code>collect(): array&lt;string, mixed&gt;</code> - Gather data from all collectors</li>
        <li><code>enable(): void</code> - Enable DebugBar</li>
        <li><code>disable(): void</code> - Disable DebugBar</li>
        <li><code>isEnabled(): bool</code> - Check if DebugBar is enabled</li>
    </ul>

    <h3>InjectDebugBar Middleware</h3>
    <p><strong>Location:</strong> <code>src/Larafony/DebugBar/Middleware/InjectDebugBar.php</code></p>
    <p><strong>Purpose:</strong> PSR-15 middleware that injects DebugBar toolbar HTML into responses.</p>

    <p><strong>Injection Logic:</strong></p>
    <ol>
        <li>Check if DebugBar is enabled - if not, return original response</li>
        <li>Check Content-Type - must contain 'text/html'</li>
        <li>Check status code - must be &lt; 400 (not error page)</li>
        <li>Find <code>&lt;/body&gt;</code> tag in response body</li>
        <li>Render toolbar view with collected data</li>
        <li>Insert toolbar HTML before <code>&lt;/body&gt;</code></li>
        <li>Return modified response</li>
    </ol>

    <p><strong>Safety Checks:</strong></p>
    <ul>
        <li>Only injects into HTML responses (not JSON, XML, etc.)</li>
        <li>Only injects into successful responses (not 404, 500, etc.)</li>
        <li>Only injects if <code>&lt;/body&gt;</code> tag exists</li>
        <li>Gracefully handles missing conditions</li>
    </ul>

    <h3>DebugBarServiceProvider</h3>
    <p><strong>Location:</strong> <code>src/Larafony/DebugBar/ServiceProviders/DebugBarServiceProvider.php</code></p>
    <p><strong>Purpose:</strong> Service provider responsible for bootstrapping DebugBar with configuration-driven collector registration.</p>

    <p><strong>Bootstrap Algorithm:</strong></p>
    <ol>
        <li>Check if DebugBar is enabled in config - <strong>early return if disabled</strong> (zero overhead in production)</li>
        <li>Create DebugBar instance</li>
        <li>Load collectors configuration from <code>config/debugbar.php</code></li>
        <li>Iterate through collector class names</li>
        <li>Resolve each collector from container (supports DI)</li>
        <li>Register collector with DebugBar</li>
        <li>Store collector instances for event listener discovery</li>
        <li>Enable DebugBar</li>
        <li>Register DebugBar singleton in container</li>
        <li>Discover and register event listeners using ListenerDiscovery</li>
    </ol>

    <div class="alert-docs alert-success">
        <i class="bi bi-speedometer2 me-2"></i>
        <strong>Performance Optimization:</strong> The provider uses an early return pattern when DebugBar is disabled, ensuring <strong>zero overhead</strong> in production environments - no collectors are instantiated, no event listeners registered, and no memory allocated for debugging infrastructure.
    </div>

    <h3>EagerRelationsLoader</h3>
    <p><strong>Location:</strong> <code>src/Larafony/Database/ORM/EagerLoading/EagerRelationsLoader.php</code></p>
    <p><strong>Purpose:</strong> Orchestrate eager loading of model relations to prevent N+1 queries.</p>

    <p><strong>Algorithm:</strong></p>
    <ol>
        <li>For each configured relation:</li>
        <li>Get relation instance from first model</li>
        <li>Determine loader type (BelongsTo, HasMany, etc.)</li>
        <li>Delegate to specific loader</li>
        <li>Pass nested relations for recursive loading</li>
    </ol>

    <h3>HasManyLoader</h3>
    <p><strong>Location:</strong> <code>src/Larafony/Database/ORM/EagerLoading/HasManyLoader.php</code></p>
    <p><strong>Purpose:</strong> Load hasMany relations efficiently with single query.</p>

    <p><strong>Algorithm:</strong></p>
    <ol>
        <li>Extract foreign_key, local_key, related class from relation via reflection</li>
        <li>Collect local key values from all parent models</li>
        <li>Execute single <code>whereIn(foreign_key, local_keys)</code> query</li>
        <li>Support nested eager loading recursively</li>
        <li>Group results by foreign key value</li>
        <li>Assign grouped arrays to parent models</li>
    </ol>

    <pre class="line-numbers"><code class="language-php">// Given: 100 users, each with multiple posts
// Without eager loading: 1 + 100 queries
// With eager loading: 1 + 1 queries

$users = User::query()->with(['posts'])->get();

// 2 queries:
// SELECT * FROM users
// SELECT * FROM posts WHERE user_id IN (1,2,3,...,100)</code></pre>

    <h2>Testing</h2>
    <p>The DebugBar and eager loading features are tested through integration tests:</p>

    <h3>DebugBar Integration Tests</h3>
    <p><strong>Coverage:</strong> Tests verify:</p>
    <ul>
        <li>Middleware injection into HTML responses</li>
        <li>Collector data gathering</li>
        <li>Event listener registration</li>
        <li>Response modification without corruption</li>
        <li>Conditional injection (only HTML, only 2xx/3xx)</li>
    </ul>

    <h3>Eager Loading Tests</h3>
    <p><strong>Coverage:</strong> Tests verify:</p>
    <ul>
        <li>N+1 query prevention</li>
        <li>Single query execution per relation</li>
        <li>Nested relation loading</li>
        <li>Multiple relation loading</li>
        <li>Relation data integrity</li>
        <li>Support for all relation types (BelongsTo, HasMany, BelongsToMany, HasManyThrough)</li>
    </ul>

    <pre class="line-numbers"><code class="language-php">// Without eager loading
$users = User::query()->get();
$this->assertQueryCount(101); // 1 + 100

// With eager loading
$users = User::query()->with(['role'])->get();
$this->assertQueryCount(2); // 1 + 1</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-book me-2"></i>
        <strong>Learn More:</strong> This implementation is explained in detail with step-by-step tutorials, tests, and best practices at <a href="https://masterphp.eu" target="_blank" style="color: #22c55e;">masterphp.eu</a>
    </div>
</x-docs-layout>
