<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Sessions & Cookies</h1>
    <p class="lead text-white-50">
        Encrypted session management with file and database storage, plus secure cookie handling
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-lock-fill me-2"></i>
        <strong>Automatic Encryption:</strong> All session data and cookies are automatically encrypted using XChaCha20-Poly1305 AEAD cipher. No middleware configuration needed.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's session and cookie system provides:
    </p>
    <ul>
        <li><strong>Automatic Encryption</strong> - All data encrypted with XChaCha20-Poly1305</li>
        <li><strong>Dual Storage</strong> - File-based and database-backed session handlers</li>
        <li><strong>PSR-20 Clock Integration</strong> - Testable time-based operations</li>
        <li><strong>Secure Defaults</strong> - HttpOnly, Secure (HTTPS), SameSite protection</li>
        <li><strong>SessionHandlerInterface</strong> - Standard PHP session handler implementation</li>
        <li><strong>Transparent API</strong> - Simple get/set interface, encryption happens automatically</li>
    </ul>

    <h2>Session Management</h2>

    <h3>Basic Session Usage</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Storage\Session\SessionManager;

// Create and start session (auto-selects handler from config)
$session = SessionManager::create();

// Store data (automatically encrypted)
$session->set('user_id', 42);
$session->set('cart', ['item1', 'item2', 'item3']);
$session->set('preferences', [
    'theme' => 'dark',
    'language' => 'en'
]);

// Retrieve data (automatically decrypted)
$userId = $session->get('user_id'); // 42
$cart = $session->get('cart', []); // ['item1', 'item2', 'item3']

// Check existence
if ($session->has('user_id')) {
    // User is logged in
}

// Get all session data
$allData = $session->all();

// Remove specific item
$session->remove('cart');

// Clear all session data
$session->clear();

// Get session ID
$sessionId = $session->getId();</code></pre>

    <h3>Session Security</h3>

    <pre class="line-numbers"><code class="language-php">// Regenerate session ID (best practice after login)
$session->regenerateId(deleteOldSession: true);

// Destroy session (logout)
$session->destroy();</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-shield-check me-2"></i>
        <strong>Security Best Practice:</strong> Always regenerate the session ID after authentication to prevent session fixation attacks.
    </div>

    <h2>Session Storage Handlers</h2>

    <h3>File-Based Sessions</h3>

    <p>Default handler, stores encrypted session data as files:</p>

    <pre class="line-numbers"><code class="language-php">// config/session.php
return [
    'handler' => \Larafony\Framework\Storage\Session\Handlers\FileSessionHandler::class,
    'path' => sys_get_temp_dir() . '/sessions',
    // or: storage_path('framework/sessions')
];</code></pre>

    <p><strong>Features:</strong></p>
    <ul>
        <li>✅ Simple, no database required</li>
        <li>✅ Automatic garbage collection of expired sessions</li>
        <li>✅ Good for single-server applications</li>
        <li>❌ Not suitable for load-balanced environments</li>
    </ul>

    <h3>Database-Backed Sessions</h3>

    <p>Recommended for production and multi-server setups:</p>

    <pre class="line-numbers"><code class="language-php">// config/session.php
return [
    'handler' => \Larafony\Framework\Storage\Session\Handlers\DatabaseSessionHandler::class,
];</code></pre>

    <p><strong>Features:</strong></p>
    <ul>
        <li>✅ Scalable - works with load balancers</li>
        <li>✅ Tracks user IP, user agent, user ID</li>
        <li>✅ Automatic expiration checking</li>
        <li>✅ ORM integration with property hooks</li>
        <li>✅ Indexed queries for performance</li>
    </ul>

    <h3>Setup Database Sessions</h3>

    <p><strong>Step 1:</strong> Generate migration</p>

    <pre class="line-numbers"><code class="language-bash">php bin/console table:session</code></pre>

    <p><strong>Step 2:</strong> Run migration</p>

    <pre class="line-numbers"><code class="language-bash">php bin/console migrate</code></pre>

    <p><strong>Step 3:</strong> Configure handler in <code>config/session.php</code></p>

    <pre class="line-numbers"><code class="language-php">return [
    'handler' => \Larafony\Framework\Storage\Session\Handlers\DatabaseSessionHandler::class,

    'cookie_params' => [
        'lifetime' => 7200, // 2 hours
        'path' => '/',
        'domain' => '',
        'secure' => true, // HTTPS only
        'httponly' => true, // No JavaScript access
        'samesite' => 'Strict', // CSRF protection
    ],
];</code></pre>

    <h3>Database Schema</h3>

    <p>The sessions table stores:</p>

    <pre class="line-numbers"><code class="language-sql">CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,           -- Session ID
    payload TEXT NOT NULL,                 -- Encrypted session data
    last_activity INT NOT NULL,            -- Unix timestamp
    user_ip VARCHAR(45) NULL,              -- IPv4 or IPv6
    user_agent TEXT NULL,                  -- Browser info
    user_id BIGINT UNSIGNED NULL,          -- Linked user (if authenticated)
    INDEX idx_last_activity (last_activity),
    INDEX idx_user_id (user_id)
);</code></pre>

    <h2>Cookie Management</h2>

    <h3>Basic Cookie Usage</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Storage\CookieManager;
use Larafony\Framework\Storage\CookieOptions;

$cookies = new CookieManager();

// Set encrypted cookie with defaults
$cookies->set('user_preferences', [
    'theme' => 'dark',
    'language' => 'en',
    'notifications' => true
]);

// Retrieve and decrypt automatically
$preferences = $cookies->get('user_preferences');
// Returns: ['theme' => 'dark', 'language' => 'en', 'notifications' => true]

// Get with default value
$settings = $cookies->get('settings', ['default' => 'value']);

// Get all cookies (decrypted)
$allCookies = $cookies->all();

// Remove cookie
$cookies->remove('user_preferences');</code></pre>

    <h3>Cookie Options</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Storage\CookieOptions;

// Custom cookie options
$options = new CookieOptions(
    expires: time() + 86400,  // 24 hours from now
    path: '/admin',           // Available only under /admin
    domain: '.example.com',   // Available on all subdomains
    secure: true,             // HTTPS only
    httponly: true,           // No JavaScript access
    samesite: 'Strict'        // Strict CSRF protection
);

$cookies->set('admin_token', 'secret_value', $options);</code></pre>

    <h3>Secure Defaults</h3>

    <p>CookieOptions provides secure defaults automatically:</p>

    <pre class="line-numbers"><code class="language-php">// Default options (without parameters):
new CookieOptions()
// Equivalent to:
new CookieOptions(
    expires: time() + 3600,           // 1 hour
    path: '/',
    domain: '',
    secure: (HTTPS detected),         // Auto-detects HTTPS
    httponly: true,                   // Always true by default
    samesite: 'Lax'                   // Balanced security
)</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>Production Tip:</strong> Always use <code>secure: true</code> and <code>samesite: 'Strict'</code> in production to maximize security.
    </div>

    <h2>Session Configuration</h2>

    <h3>Complete Configuration Example</h3>

    <pre class="line-numbers"><code class="language-php">// config/session.php
use Larafony\Framework\Config\Environment\EnvReader;
use Larafony\Framework\Storage\Session\Handlers\DatabaseSessionHandler;
use Larafony\Framework\Storage\Session\Handlers\FileSessionHandler;

return [
    // Session handler: file or database
    'handler' => EnvReader::read('SESSION_DRIVER') === 'database'
        ? DatabaseSessionHandler::class
        : FileSessionHandler::class,

    // File storage path (for FileSessionHandler)
    'path' => EnvReader::read('SESSION_PATH', sys_get_temp_dir() . '/sessions'),

    // Cookie configuration
    'cookie_params' => [
        'lifetime' => (int) EnvReader::read('SESSION_LIFETIME', '7200'),
        'path' => EnvReader::read('SESSION_PATH_COOKIE', '/'),
        'domain' => EnvReader::read('SESSION_DOMAIN', ''),
        'secure' => EnvReader::read('SESSION_SECURE_COOKIE') === 'true'
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => EnvReader::read('SESSION_HTTP_ONLY', 'true') === 'true',
        'samesite' => EnvReader::read('SESSION_SAME_SITE', 'Lax'),
    ],
];</code></pre>

    <h3>Environment Variables</h3>

    <pre class="line-numbers"><code class="language-bash"># .env file

# Session driver: file or database
SESSION_DRIVER=database

# Session lifetime (seconds)
SESSION_LIFETIME=7200

# File storage path (if using file driver)
SESSION_PATH=/var/www/storage/sessions

# Cookie settings
SESSION_PATH_COOKIE=/
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=Strict</code></pre>

    <h2>How Encryption Works</h2>

    <h3>Session Encryption</h3>

    <p>Sessions are encrypted at the storage layer:</p>

    <pre class="line-numbers"><code class="language-php">// SessionSecurity class (used by both handlers)
class SessionSecurity
{
    public function encrypt(string $data): string
    {
        $encryptor = new EncryptionService();
        return $encryptor->encrypt($data);
    }

    public function decrypt(string $encrypted): string
    {
        $encryptor = new EncryptionService();
        return $encryptor->decrypt($encrypted);
    }
}

// FileSessionHandler writes encrypted data
public function write(string $id, string $data): bool
{
    $encrypted = $this->security->encrypt($data);
    file_put_contents($this->getFilePath($id), $encrypted);
    return true;
}

// DatabaseSessionHandler writes encrypted data
public function write(string $id, string $data): bool
{
    $encrypted = $this->security->encrypt($data);
    $session->payload = $encrypted;
    $session->save();
    return true;
}</code></pre>

    <h3>Cookie Encryption</h3>

    <p>Cookies are transparently encrypted/decrypted:</p>

    <pre class="line-numbers"><code class="language-php">// CookieManager automatically encrypts
public function set(string $name, mixed $value, CookieOptions $options): void
{
    $encrypted = new EncryptionService()->encrypt($value);
    setcookie($name, $encrypted, $options->toArray());
}

// CookieManager automatically decrypts
public function get(string $name, mixed $default = null): mixed
{
    $value = $_COOKIE[$name] ?? null;

    if ($value === null) {
        return $default;
    }

    return new EncryptionService()->decrypt($value);
}</code></pre>

    <h2>Comparison with Other Frameworks</h2>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Feature</th>
                <th>Larafony</th>
                <th>Laravel</th>
                <th>Symfony</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Session Encryption</td>
                <td><span class="badge bg-success">Automatic</span></td>
                <td>Optional (middleware)</td>
                <td>Manual (bundles)</td>
            </tr>
            <tr>
                <td>Cookie Encryption</td>
                <td><span class="badge bg-success">Automatic</span></td>
                <td>Middleware required</td>
                <td>Manual (bundles)</td>
            </tr>
            <tr>
                <td>Storage Handlers</td>
                <td>File, Database</td>
                <td>File, DB, Redis, Array</td>
                <td>File, PDO, Redis, Memcached</td>
            </tr>
            <tr>
                <td>Configuration</td>
                <td>Simple config file</td>
                <td>.env + config files</td>
                <td>YAML/PHP config</td>
            </tr>
            <tr>
                <td>Cipher</td>
                <td><span class="badge bg-success">XChaCha20-Poly1305</span></td>
                <td>AES-256-CBC + HMAC</td>
                <td>Varies</td>
            </tr>
            <tr>
                <td>Clock Integration</td>
                <td><span class="badge bg-success">PSR-20</span></td>
                <td>Carbon (not PSR)</td>
                <td>DateTime/Clock</td>
            </tr>
        </tbody>
    </table>

    <h2>Use Cases</h2>

    <h3>User Authentication</h3>

    <pre class="line-numbers"><code class="language-php">// Login
public function login(User $user): void
{
    $session = SessionManager::create();

    // Regenerate ID to prevent session fixation
    $session->regenerateId(deleteOldSession: true);

    // Store user data
    $session->set('user_id', $user->id);
    $session->set('user_role', $user->role);
    $session->set('authenticated_at', time());
}

// Check authentication
public function isAuthenticated(): bool
{
    $session = SessionManager::create();
    return $session->has('user_id');
}

// Logout
public function logout(): void
{
    $session = SessionManager::create();
    $session->destroy();
}</code></pre>

    <h3>Shopping Cart</h3>

    <pre class="line-numbers"><code class="language-php">class CartManager
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = SessionManager::create();
    }

    public function addItem(int $productId, int $quantity): void
    {
        $cart = $this->session->get('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->session->set('cart', $cart);
    }

    public function getItems(): array
    {
        return $this->session->get('cart', []);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }
}</code></pre>

    <h3>Remember Me Cookie</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Storage\CookieManager;
use Larafony\Framework\Storage\CookieOptions;

// Set remember me token (30 days)
public function setRememberToken(User $user): void
{
    $token = bin2hex(random_bytes(32));

    // Store in database
    $user->remember_token = hash('sha256', $token);
    $user->save();

    // Store in encrypted cookie
    $cookies = new CookieManager();
    $cookies->set('remember_token', $token, new CookieOptions(
        expires: time() + (30 * 86400), // 30 days
        secure: true,
        httponly: true,
        samesite: 'Strict'
    ));
}

// Check remember me token
public function checkRememberToken(): ?User
{
    $cookies = new CookieManager();
    $token = $cookies->get('remember_token');

    if (!$token) {
        return null;
    }

    $hashedToken = hash('sha256', $token);
    return User::query()
        ->where('remember_token', '=', $hashedToken)
        ->first();
}</code></pre>

    <h2>Best Practices</h2>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Do's:</strong>
        <ul class="mb-0 mt-2">
            <li>✅ Always regenerate session ID after login</li>
            <li>✅ Use database sessions for load-balanced environments</li>
            <li>✅ Set appropriate session lifetime (2-8 hours)</li>
            <li>✅ Use <code>secure: true</code> in production (HTTPS)</li>
            <li>✅ Use <code>samesite: 'Strict'</code> for sensitive operations</li>
            <li>✅ Clean up sessions on logout with <code>destroy()</code></li>
        </ul>
    </div>

    <div class="alert-docs alert-danger">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong>Don'ts:</strong>
        <ul class="mb-0 mt-2">
            <li>❌ Don't store sensitive data in cookies (use sessions instead)</li>
            <li>❌ Don't use file sessions in load-balanced environments</li>
            <li>❌ Don't set session lifetime too long (security risk)</li>
            <li>❌ Don't forget to call <code>regenerateId()</code> after login</li>
            <li>❌ Don't disable <code>httponly</code> flag (XSS protection)</li>
        </ul>
    </div>

    <h2>Next Steps</h2>

    <ul>
        <li><a href="/docs/encryption">Encryption</a> - Learn about the underlying encryption system</li>
        <li><a href="/docs/config">Configuration</a> - Configure session and cookie settings</li>
        <li><a href="/docs/middleware">Middleware</a> - Create custom session middleware</li>
        <li><a href="/docs/models">Models & ORM</a> - Store session data in database with ORM</li>
    </ul>

    <h2>Learn More</h2>

    <p>
        This implementation is explained in detail with step-by-step tutorials, tests, and best practices at
        <a href="https://masterphp.eu" target="_blank" class="text-decoration-none" style="color: #ec4899;">
            <strong>masterphp.eu</strong> <i class="bi bi-arrow-up-right-square"></i>
        </a>
    </p>

    <div class="alert-docs alert-success mt-4">
        <i class="bi bi-rocket-takeoff-fill me-2"></i>
        <strong>Demo App:</strong> See encrypted sessions and cookies in production with file and database storage handlers. The demo application showcases automatic encryption, PSR-20 clock integration, and secure cookie defaults with HttpOnly and SameSite protection.
        <br><br>
        <a href="https://packagist.org/packages/larafony/skeleton" target="_blank" class="btn btn-sm btn-success me-2">
            <i class="bi bi-box-seam me-1"></i> View on Packagist
        </a>
        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-sm btn-outline-light">
            <i class="bi bi-github me-1"></i> View on GitHub
        </a>
    </div>
</x-docs-layout>
