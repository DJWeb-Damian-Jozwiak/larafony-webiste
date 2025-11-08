<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Cache Optimization</h1>
    <p class="lead text-white-50">
        Enterprise-grade PSR-6 caching with multi-backend support, intelligent optimization, and authorization integration
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-lightning-charge-fill me-2"></i>
        <strong>Performance First:</strong> Built-in in-memory caching, automatic compression, LRU eviction, and cache warming—designed for high-traffic production environments.
    </div>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>PHP 8.5 Extension Notice (Nov. 8, 2025):</strong> As of November 8, 2025, there are no official builds for Redis and Memcached extensions for PHP 8.5.
        <ul class="mt-2 mb-0">
            <li><strong>Recommended:</strong> Use <strong>FileStorage</strong> driver (works out of the box)</li>
            <li><strong>Advanced:</strong> Want Redis/Memcached? Run <code>./build.sh</code> from your project root after <code>composer create-project</code> to compile extensions from source</li>
        </ul>
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's cache system provides:
    </p>
    <ul>
        <li><strong>PSR-6 Compliance</strong> - Full CacheItemPool and CacheItem implementation</li>
        <li><strong>Multi-Backend</strong> - File, Redis, and Memcached with unified interface</li>
        <li><strong>In-Memory Cache</strong> - LRU eviction for same-request optimization (prevents memory leaks)</li>
        <li><strong>Auto Compression</strong> - Values > 10KB automatically compressed with gzcompress</li>
        <li><strong>Tagged Cache</strong> - Group invalidation with <code>tags(['users', 'statistics'])</code></li>
        <li><strong>Cache Warming</strong> - Preload frequently accessed data with fluent API</li>
        <li><strong>Auth Integration</strong> - Built-in caching for roles and permissions (1-hour TTL)</li>
    </ul>

    <h2>Quick Start</h2>

    <h3>Basic Usage</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Cache\Cache;

$cache = Cache::instance();

// Store value for 1 hour (3600 seconds)
$cache->put('user.profile.123', ['name' => 'Alice'], 3600);

// Retrieve value
$profile = $cache->get('user.profile.123');

// Remember (get or set)
$stats = $cache->remember('statistics.users', 3600, function() {
    return User::count(); // Only executed if cache miss
});

// Check existence
if ($cache->has('products.featured')) {
    // Cache hit
}

// Remove item
$cache->forget('temporary.data');

// Clear all cache
$cache->flush();</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-speedometer2 me-2"></i>
        <strong>In-Memory Optimization:</strong> Repeated <code>get()</code> calls in the same request return from memory—no backend hit. LRU eviction prevents memory leaks in long-running processes.
    </div>

    <h2>Tagged Cache</h2>

    <p>Group related cache items for efficient bulk invalidation:</p>

    <pre class="line-numbers"><code class="language-php">// Cache with tags
$cache->tags(['users', 'statistics'])
    ->put('users.total', 1500, 3600);

$cache->tags(['users', 'active'])
    ->put('users.active.count', 420, 3600);

$cache->tags(['statistics', 'reports'])
    ->put('monthly.revenue', 125000, 7200);

// Flush all items tagged with 'users'
$cache->tags(['users'])->flush();
// Clears: users.total and users.active.count
// Keeps: monthly.revenue (only tagged with 'statistics' and 'reports')

// Check tagged cache
if ($cache->tags(['users'])->has('users.total')) {
    // Item exists
}</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-info-circle me-2"></i>
        <strong>PSR-6 Compliance:</strong> Cache keys cannot contain reserved characters <code>{}()/\@:</code> and must be ≤ 64 characters. Tagged cache uses <code>.</code> separator for compliance.
    </div>

    <h2>Cache Warming</h2>

    <p>Preload frequently accessed data to eliminate cold cache performance degradation:</p>

    <h3>Register Warmers (Bootstrap File)</h3>

    <pre class="line-numbers"><code class="language-php">// bootstrap/cache-warmers.php
use Larafony\Framework\Cache\Cache;
use App\Models\{User, Product, Category};

$cache = Cache::instance();
$warmer = $cache->warmer();

// Register multiple warmers with fluent interface
$warmer
    ->register(
        key: 'statistics.total_users',
        callback: fn() => User::count(),
        ttl: 3600,
        tags: ['statistics', 'users']
    )
    ->register(
        key: 'products.featured',
        callback: fn() => Product::where('featured', 1)
            ->orderBy('sales', 'desc')
            ->limit(10)
            ->get()
            ->toArray(),
        ttl: 1800,
        tags: ['products', 'homepage']
    )
    ->register(
        key: 'categories.tree',
        callback: fn() => Category::buildHierarchy(),
        ttl: 7200,
        tags: ['categories']
    );</code></pre>

    <h3>Console Commands</h3>

    <pre class="line-numbers"><code class="language-bash"># Warm all registered caches
php bin/console cache:warm

# Force refresh (overwrite existing)
php bin/console cache:warm --force

# Warm in batches (for large datasets)
php bin/console cache:warm --batch=20

# Clear cache
php bin/console cache:clear

# Clear specific tags
php bin/console cache:clear users statistics</code></pre>

    <h3>Manual Warming</h3>

    <pre class="line-numbers"><code class="language-php">$warmer = $cache->warmer();

// Warm all registered caches
$result = $warmer->warmAll();
// Returns: ['total' => 3, 'warmed' => 2, 'skipped' => 1, 'failed' => 0]

// Warm in batches (100μs sleep between batches)
$result = $warmer->warmInBatches(batchSize: 10, force: false);

// Warm single key immediately
$warmer->warm('config.settings', fn() => Config::all(), 3600);</code></pre>

    <h2>Cached Authorization</h2>

    <p>Built-in caching for roles and permissions significantly reduces database queries:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\ORM\Entities\User;

$user = User::find(123);

// First call: SELECT roles, permissions + cache for 1 hour
if ($user->hasRole('admin')) {
    // Database query executed
}

// Second call: Returns from cache (no DB query)
if ($user->hasRole('admin')) {
    // Instant response from cache
}

// Permission check (cached for 1 hour)
if ($user->hasPermission('users.create')) {
    // Caches ALL user permissions across ALL roles
}

// Automatic cache invalidation on role changes
$editorRole = Role::where('name', 'editor')->first();
$user->addRole($editorRole);
// Clears: user.123.roles and user.123.permissions

// Next check refreshes cache from database
$user->hasPermission('posts.edit'); // Fresh query + new cache</code></pre>

    <h3>Cascading Invalidation</h3>

    <p>When role permissions change, all users with that role are automatically invalidated:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\ORM\Entities\{Role, Permission};

$adminRole = Role::where('name', 'admin')->first();

// Add permission to role
$publishPosts = Permission::where('name', 'posts.publish')->first();
$adminRole->addPermission($publishPosts);

// Automatically clears cache for:
// - role.{id}.permissions (this role)
// - user.{id}.roles (ALL users with admin role)
// - user.{id}.permissions (ALL users with admin role)

// All admin users will get fresh permissions on next check
foreach ($adminRole->users as $user) {
    $user->hasPermission('posts.publish'); // Fresh from database
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-database me-2"></i>
        <strong>Performance Impact:</strong> Without caching, <code>hasPermission()</code> executes 2-3 database queries. With caching, subsequent checks in the same hour are instant (0 queries).
    </div>

    <h2>Storage Backends</h2>

    <h3>File Storage</h3>

    <p>File-based caching with LRU eviction and metadata tracking:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Cache\Storage\FileStorage;

$storage = new FileStorage('/var/cache/app');

// Set maximum items (LRU eviction when exceeded)
$storage->maxCapacity(1000);

// Automatic eviction
for ($i = 0; $i < 1001; $i++) {
    $storage->set("key.$i", ['value' => "data$i", 'expiry' => time() + 3600]);
}
// Oldest item automatically removed</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-file-earmark-text me-2"></i>
        <strong>Access Log:</strong> FileStorage maintains <code>meta.json</code> with access times for efficient LRU eviction.
    </div>

    <h3>Redis Storage</h3>

    <p>High-performance Redis backend with atomic operations and batch support:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Cache\Storage\RedisStorage;
use Larafony\Framework\Cache\Enums\RedisEvictionPolicy;

$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);
$storage = new RedisStorage($redis, prefix: 'app:');

// Configure eviction policy
$storage->withEvictionPolicy(RedisEvictionPolicy::ALLKEYS_LFU);
$storage->maxCapacity(512 * 1024 * 1024); // 512MB

// Atomic counter operations (race-condition safe)
$storage->increment('api.requests.count', 1);
$storage->increment('user.123.points', 10);
$storage->decrement('inventory.product.456', 1);

// Batch operations using pipeline
$items = [
    'product.1' => ['value' => ['name' => 'Widget'], 'expiry' => time() + 3600],
    'product.2' => ['value' => ['name' => 'Gadget'], 'expiry' => time() + 3600],
];
$storage->setMultiple($items); // Single pipeline execution

$values = $storage->getMultiple(['product.1', 'product.2']);
$storage->deleteMultiple(['old.key.1', 'old.key.2']);</code></pre>

    <h4>Available Eviction Policies</h4>

    <ul>
        <li><code>ALLKEYS_LRU</code> - Remove least recently used keys</li>
        <li><code>ALLKEYS_LFU</code> - Remove least frequently used keys (recommended)</li>
        <li><code>VOLATILE_LRU</code> - Remove LRU among keys with expiry set</li>
        <li><code>VOLATILE_LFU</code> - Remove LFU among keys with expiry set</li>
        <li><code>VOLATILE_TTL</code> - Remove keys with nearest expiry time</li>
        <li><code>ALLKEYS_RANDOM</code> - Remove random keys</li>
        <li><code>VOLATILE_RANDOM</code> - Remove random keys with expiry set</li>
        <li><code>NOEVICTION</code> - Return errors when memory limit reached</li>
    </ul>

    <h3>Memcached Storage</h3>

    <p>Distributed Memcached caching with automatic TTL handling:</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Cache\Storage\MemcachedStorage;

$memcached = new \Memcached();
$memcached->addServer('cache1.example.com', 11211);
$memcached->addServer('cache2.example.com', 11211); // Multi-server
$storage = new MemcachedStorage($memcached);

// Memcached automatically removes expired items
$storage->set('session.abc123', [
    'value' => ['user_id' => 42, 'preferences' => [...]],
    'expiry' => time() + 1800 // 30 minutes
]);

// After 30 minutes: Memcached automatically deleted the key
$data = $storage->get('session.abc123'); // Returns null</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Memcached Limitation:</strong> <code>clear()</code> uses <code>flush()</code> which clears ALL data from Memcached instance, not just prefixed keys. Use separate Memcached instance per application.
    </div>

    <h2>Automatic Compression</h2>

    <p>Values exceeding the compression threshold are automatically compressed:</p>

    <pre class="line-numbers"><code class="language-php">// Default: compress values > 10KB
$largeData = str_repeat('Lorem ipsum dolor sit amet. ', 1000); // ~27KB
$cache->put('large.document', $largeData, 3600);
// Automatically compressed with gzcompress (level 6)

// Customize compression settings
$storage->withCompression(enabled: true)
    ->withCompressionThreshold(bytes: 5120); // Compress values > 5KB

// Disable compression
$storage->withCompression(enabled: false);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-archive me-2"></i>
        <strong>Space Savings:</strong> Text-heavy data typically compresses 70-90%. Binary data (images, PDFs) compresses less effectively and may be skipped.
    </div>

    <h2>Configuration</h2>

    <div class="alert-docs alert-success">
        <i class="bi bi-star-fill me-2"></i>
        <strong>Larafony's Unique Feature:</strong> Unlike Laravel (which requires complex workarounds), Larafony natively supports <strong>multiple cache stores per driver</strong> with independent configuration. Need 3 different Redis instances with different prefixes? Just add them to config!
    </div>

    <h3>Basic Configuration (config/cache.php)</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Config\Environment\EnvReader;

return [
    'default' => EnvReader::read('CACHE_DRIVER', 'file'),

    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => EnvReader::read('CACHE_FILE_PATH', 'storage/cache'),
        ],

        'redis' => [
            'driver' => 'redis',
            'host' => EnvReader::read('REDIS_HOST', '127.0.0.1'),
            'port' => (int) EnvReader::read('REDIS_PORT', '6379'),
            'database' => (int) EnvReader::read('REDIS_CACHE_DB', '1'),
            'password' => EnvReader::read('REDIS_PASSWORD', null),
            'prefix' => EnvReader::read('REDIS_PREFIX', 'larafony:cache:'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'host' => EnvReader::read('MEMCACHED_HOST', '127.0.0.1'),
            'port' => (int) EnvReader::read('MEMCACHED_PORT', '11211'),
            'prefix' => EnvReader::read('MEMCACHED_PREFIX', 'larafony:cache:'),
        ],
    ],
];</code></pre>

    <h3>Multi-Store Configuration (The Game Changer 🔥)</h3>

    <p>Define multiple stores of the same driver type with different settings:</p>

    <pre class="line-numbers"><code class="language-php">return [
    'default' => 'redis',

    'stores' => [
        // Primary Redis for API cache
        'redis' => [
            'driver' => 'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 1,
            'prefix' => 'api:cache:',
        ],

        // Second Redis for sessions (different server!)
        'redis_sessions' => [
            'driver' => 'redis',
            'host' => '192.168.1.100',
            'port' => 6379,
            'database' => 2,
            'prefix' => 'sessions:',
        ],

        // Third Redis for background jobs
        'redis_jobs' => [
            'driver' => 'redis',
            'host' => 'redis.production.local',
            'port' => 6379,
            'database' => 3,
            'prefix' => 'jobs:',
        ],

        // Memcached for distributed cache
        'memcached' => [
            'driver' => 'memcached',
            'host' => 'cache-cluster-1.local',
            'port' => 11211,
            'prefix' => 'app:',
        ],

        // Second Memcached cluster
        'memcached_global' => [
            'driver' => 'memcached',
            'host' => 'cache-cluster-2.local',
            'port' => 11211,
            'prefix' => 'global:',
        ],

        // File storage for local development
        'file' => [
            'driver' => 'file',
            'path' => 'storage/cache',
        ],
    ],
];</code></pre>

    <h3>Using Multiple Stores</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Cache\Cache;

$cache = Cache::instance();

// Use default store
$cache->put('user.1', $userData);

// Switch to specific store
$cache->store('redis_sessions')->put('session.abc', $sessionData);
$cache->store('redis_jobs')->put('job.123', $jobData);

// Each store maintains its own prefix
$apiCache = $cache->store('redis');           // Keys: api:cache:*
$sessionCache = $cache->store('redis_sessions'); // Keys: sessions:*
$jobCache = $cache->store('redis_jobs');         // Keys: jobs:*

// No key collisions!
$apiCache->put('user.1', $apiData);        // Stored as: api:cache:user.1
$sessionCache->put('user.1', $sessionData); // Stored as: sessions:user.1
$jobCache->put('user.1', $jobData);         // Stored as: jobs:user.1</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Why This Matters:</strong>
        <ul class="mb-0 mt-2">
            <li><strong>Isolation:</strong> Different app parts use separate stores without collisions</li>
            <li><strong>Performance:</strong> Critical caches on dedicated Redis with optimized settings</li>
            <li><strong>Security:</strong> Sensitive data (sessions) on separate server with strict access</li>
            <li><strong>Scalability:</strong> Each store scales independently</li>
            <li><strong>Zero Workarounds:</strong> Native support, just call <code>->store('name')</code></li>
        </ul>
    </div>

    <h3>Environment Variables</h3>

    <pre class="line-numbers"><code class="language-bash"># .env
CACHE_DRIVER=redis
CACHE_FILE_PATH=storage/cache

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_CACHE_DB=1
REDIS_PASSWORD=null
REDIS_PREFIX=larafony:cache:

# Memcached
MEMCACHED_HOST=127.0.0.1
MEMCACHED_PORT=11211
MEMCACHED_PREFIX=larafony:cache:</code></pre>

    <h2>Best Practices</h2>

    <h3>Cache Key Naming</h3>

    <pre class="line-numbers"><code class="language-php">// ✅ Good: Hierarchical, descriptive
'user.123.profile'
'product.456.details'
'statistics.daily.2024-11-08'
'config.features.enabled'

// ❌ Bad: Too long, special characters
'user:profile:123:with:all:related:data:and:permissions' // > 64 chars
'config/settings' // Contains reserved character /
'cache@key' // Contains reserved character @</code></pre>

    <h3>TTL Selection</h3>

    <ul>
        <li><strong>5-15 minutes:</strong> Real-time data (stock prices, live scores)</li>
        <li><strong>30-60 minutes:</strong> Dynamic content (user profiles, recommendations)</li>
        <li><strong>1-4 hours:</strong> Semi-static data (product catalogs, categories)</li>
        <li><strong>12-24 hours:</strong> Static content (configuration, translations)</li>
        <li><strong>Forever:</strong> Immutable data (use <code>put()</code> without TTL, invalidate manually)</li>
    </ul>

    <h3>Cache Invalidation Strategy</h3>

    <pre class="line-numbers"><code class="language-php">// ✅ Use tags for related data
$cache->tags(['users', 'statistics'])
    ->put('users.count', User::count(), 3600);

$cache->tags(['users', 'reports'])
    ->put('users.monthly.growth', $data, 3600);

// Invalidate all user-related caches
$cache->tags(['users'])->flush();

// ✅ Manual invalidation on data changes
public function updateProfile(array $data): void
{
    $this->update($data);

    // Clear related caches
    $cache->forget("user.{$this->id}.profile");
    $cache->tags(['users'])->flush();
}

// ❌ Avoid: Forgetting to invalidate
public function updateProfile(array $data): void
{
    $this->update($data);
    // Stale cache remains! Users see old data
}</code></pre>

    <h3>Cold Cache Mitigation</h3>

    <pre class="line-numbers"><code class="language-php">// Register warmers for critical data
$warmer->register('homepage.featured', fn() => Product::featured(), 1800)
       ->register('nav.categories', fn() => Category::tree(), 3600)
       ->register('config.settings', fn() => Settings::all(), 7200);

// Warm after deployment
// CI/CD: php bin/console cache:warm --force

// Warm during off-peak hours (cron)
// 0 3 * * * cd /app && php bin/console cache:warm --force</code></pre>

    <h2>Performance Tips</h2>

    <div class="alert-docs alert-success">
        <i class="bi bi-speedometer me-2"></i>
        <strong>Benchmark Results:</strong> With Redis + in-memory cache, repeated <code>get()</code> calls are 500-1000x faster than database queries (0.01ms vs 5-10ms).
    </div>

    <h3>Choose the Right Backend</h3>

    <table class="table table-dark table-striped">
        <thead>
        <tr>
            <th>Backend</th>
            <th>Use Case</th>
            <th>Pros</th>
            <th>Cons</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><strong>File</strong></td>
            <td>Small apps, development</td>
            <td>No dependencies, simple</td>
            <td>Slow on high traffic, no distribution</td>
        </tr>
        <tr>
            <td><strong>Redis</strong></td>
            <td>High traffic, distributed</td>
            <td>Fast, persistent, atomic ops</td>
            <td>Requires Redis server</td>
        </tr>
        <tr>
            <td><strong>Memcached</strong></td>
            <td>Pure memory cache</td>
            <td>Extremely fast, distributed</td>
            <td>No persistence, limited features</td>
        </tr>
        </tbody>
    </table>

    <h3>Optimization Checklist</h3>

    <ul>
        <li>✅ Enable in-memory cache (default: on, 1000 items)</li>
        <li>✅ Use compression for large values (default: > 10KB)</li>
        <li>✅ Warm critical caches after deployment</li>
        <li>✅ Use tags for group invalidation</li>
        <li>✅ Set appropriate TTLs (don't cache everything forever)</li>
        <li>✅ Monitor cache hit/miss ratio</li>
        <li>✅ Use batch operations (setMultiple, getMultiple) when possible</li>
        <li>✅ Configure Redis eviction policy (ALLKEYS_LFU recommended)</li>
        <li>❌ Don't cache sensitive data without encryption</li>
        <li>❌ Don't use file cache in production with high traffic</li>
    </ul>

    <h2>Testing</h2>

    <p>All cache storage backends are tested with identical test suites using PHPUnit DataProvider:</p>

    <pre class="line-numbers"><code class="language-bash"># Run all cache tests
vendor/bin/phpunit tests/Larafony/Cache

# Results:
# - CacheWarmerTest: 12 tests ✓
# - StorageTest: 51 tests (17 tests × 3 backends) ✓
# - CachedAuthorizationTest: 5 tests ✓
# Total: 68 tests, 161+ assertions, 100% pass rate</code></pre>

    <h3>Test Example (DataProvider Pattern)</h3>

    <pre class="line-numbers"><code class="language-php">class StorageTest extends TestCase
{
    public static function storageProvider(): array
    {
        return [
            'file' => ['type' => 'file', 'factory' => fn() => new FileStorage(...)],
            'redis' => ['type' => 'redis', 'factory' => fn() => new RedisStorage(...)],
            'memcached' => ['type' => 'memcached', 'factory' => fn() => new MemcachedStorage(...)],
        ];
    }

    #[DataProvider('storageProvider')]
    public function testSetAndGet(string $type, callable $factory): void
    {
        $storage = $factory();

        // Same assertions for all backends
        $storage->set('key', ['value' => 'data', 'expiry' => time() + 3600]);
        $this->assertNotNull($storage->get('key'));
    }
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-check-circle me-2"></i>
        <strong>Behavior Guarantee:</strong> DataProvider testing ensures FileStorage, RedisStorage, and MemcachedStorage behave identically for the same operations.
    </div>

    <h2>Troubleshooting</h2>

    <h3>Cache Not Working</h3>

    <pre class="line-numbers"><code class="language-php">// Check if cache driver is configured
$cache = Cache::instance();
var_dump($cache); // Should not be null

// Test basic operations
$cache->put('test', 'value', 60);
var_dump($cache->get('test')); // Should return 'value'

// Check backend connection
// Redis
$redis = new \Redis();
$connected = $redis->connect('127.0.0.1', 6379);
var_dump($connected); // Should be true

// Memcached
$memcached = new \Memcached();
$memcached->addServer('127.0.0.1', 11211);
$memcached->set('test', 1);
var_dump($memcached->getResultCode() === \Memcached::RES_SUCCESS);</code></pre>

    <h3>Cache Keys Rejected</h3>

    <pre class="line-numbers"><code class="language-php">// ❌ PSR-6 violation: Reserved characters
$cache->put('user:123', $data); // Contains ':'
// Error: Cache key "user:123" contains invalid characters

// ✅ Fix: Use allowed separators
$cache->put('user.123', $data);
$cache->put('user_123', $data);
$cache->put('user-123', $data);</code></pre>

    <h3>Authorization Cache Not Clearing</h3>

    <pre class="line-numbers"><code class="language-php">// Manual cache clearing
$user->clearAuthCache();

// Or force refresh on next check
$cache->forget("user.{$user->id}.roles");
$cache->forget("user.{$user->id}.permissions");

// Role permission changes should auto-clear
$role->addPermission($permission); // Cascading invalidation
// Clears cache for ALL users with this role</code></pre>

    <h2>API Reference</h2>

    <h3>Cache Class</h3>

    <div class="method-signature">
        <code>put(string $key, mixed $value, DateInterval|int|null $ttl): bool</code>
    </div>
    <p>Store value in cache with optional TTL (seconds)</p>

    <div class="method-signature">
        <code>get(string $key, mixed $default = null): mixed</code>
    </div>
    <p>Retrieve value from cache, return default if not found</p>

    <div class="method-signature">
        <code>remember(string $key, DateInterval|int $ttl, callable $callback): mixed</code>
    </div>
    <p>Get value from cache or execute callback and store result</p>

    <div class="method-signature">
        <code>has(string $key): bool</code>
    </div>
    <p>Check if key exists in cache</p>

    <div class="method-signature">
        <code>forget(string $key): bool</code>
    </div>
    <p>Remove item from cache</p>

    <div class="method-signature">
        <code>flush(): bool</code>
    </div>
    <p>Clear all cached items</p>

    <div class="method-signature">
        <code>tags(array $tags): TaggedCache</code>
    </div>
    <p>Create tagged cache instance for group invalidation</p>

    <div class="method-signature">
        <code>warmer(): CacheWarmer</code>
    </div>
    <p>Get CacheWarmer instance for preloading data</p>

    <h3>CacheWarmer Class</h3>

    <div class="method-signature">
        <code>register(string $key, callable $callback, DateInterval|int|null $ttl, array $tags): self</code>
    </div>
    <p>Register cache warmer with key, value generator, TTL, and tags</p>

    <div class="method-signature">
        <code>warmAll(bool $force = false): array</code>
    </div>
    <p>Warm all registered caches, returns statistics array</p>

    <div class="method-signature">
        <code>warmInBatches(int $batchSize, bool $force): array</code>
    </div>
    <p>Warm caches in batches with sleep between batches</p>

    <div class="alert-docs alert-info">
        <i class="bi bi-book me-2"></i>
        <strong>Full Documentation:</strong> Complete API reference with all methods, parameters, and return types available at <a href="https://masterphp.eu" class="text-info">masterphp.eu</a>
    </div>

</x-docs-layout>
