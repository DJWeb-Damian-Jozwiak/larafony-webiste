<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Encryption</h1>
    <p class="lead text-white-50">
        Modern encryption using libsodium's XChaCha20-Poly1305 AEAD cipher for maximum security
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-shield-lock-fill me-2"></i>
        <strong>Modern Cryptography:</strong> Uses XChaCha20-Poly1305 AEAD cipher from libsodium, providing both encryption and authentication in a single operation.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's encryption system provides:
    </p>
    <ul>
        <li><strong>XChaCha20-Poly1305</strong> - Modern AEAD (Authenticated Encryption with Associated Data) cipher</li>
        <li><strong>libsodium</strong> - Industry-standard cryptographic library</li>
        <li><strong>Automatic Authentication</strong> - Prevents tampering with encrypted data</li>
        <li><strong>Constraint-Driven Validation</strong> - Five specialized assertion classes for safe error handling</li>
        <li><strong>Sensitive Parameter Protection</strong> - Uses <code>#[SensitiveParameter]</code> to redact values from stack traces</li>
    </ul>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>vs Laravel:</strong> Laravel uses AES-256-CBC with OpenSSL + separate HMAC. Larafony uses XChaCha20-Poly1305, which is faster, more secure against timing attacks, and combines encryption + authentication in one operation.
    </div>

    <h2>Key Generation</h2>

    <h3>Generate Encryption Key (CLI)</h3>

    <p>The easiest way to generate a secure encryption key:</p>

    <pre class="line-numbers"><code class="language-bash">php bin/console key:generate</code></pre>

    <p>
        This command automatically generates a cryptographically secure 32-byte key using libsodium,
        encodes it as base64, and stores it in your <code>.env</code> file as <code>APP_KEY</code>.
    </p>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Recommended:</strong> Use the <code>key:generate</code> command for production. It automatically handles key generation and .env file updates safely.
    </div>

    <h3>Generate Key Programmatically</h3>

    <p>If you need to generate keys in code (for testing or custom scenarios):</p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Encryption\KeyGenerator;

$generator = new KeyGenerator();
$key = $generator->generateKey();

// Returns base64-encoded 32-byte key
// Example: base64:Hj3kL9mN2pQ5rS8tU0vW1xY4zA6bC7dE9fG0hI2jK3l=</code></pre>

    <h3>.env File Format</h3>

    <pre class="line-numbers"><code class="language-bash"># .env file (automatically set by key:generate)
APP_KEY=base64:Hj3kL9mN2pQ5rS8tU0vW1xY4zA6bC7dE9fG0hI2jK3l=</code></pre>

    <div class="alert-docs alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>Security Warning:</strong> Never commit your <code>APP_KEY</code> to version control. Keep it secret and rotate it periodically in production.
    </div>

    <h2>Basic Encryption</h2>

    <h3>Encrypt Data</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Encryption\EncryptionService;

$encryptor = new EncryptionService();

// Encrypt any value (strings, arrays, objects)
$encrypted = $encryptor->encrypt('secret message');

// Encrypt complex data
$encrypted = $encryptor->encrypt([
    'user_id' => 123,
    'api_token' => 'abc123xyz',
    'expires_at' => '2025-12-31'
]);</code></pre>

    <h3>Decrypt Data</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Encryption\EncryptionService;

$encryptor = new EncryptionService();

try {
    $decrypted = $encryptor->decrypt($encrypted);
    // Returns original value
} catch (\InvalidArgumentException $e) {
    // Decryption failed - data corrupted or wrong key
    // Possible errors:
    // - Invalid base64 encoding
    // - Data too short
    // - Decryption failed (tampered data)
}</code></pre>

    <h2>How It Works</h2>

    <h3>Encryption Process</h3>

    <ol>
        <li><strong>Key Validation</strong> - Validates APP_KEY exists and is 32 bytes when decoded</li>
        <li><strong>Serialization</strong> - Converts value to string using PHP's serialize()</li>
        <li><strong>Nonce Generation</strong> - Creates random 24-byte nonce for this encryption</li>
        <li><strong>AEAD Encryption</strong> - Uses XChaCha20-Poly1305 to encrypt + authenticate in one step</li>
        <li><strong>Combine & Encode</strong> - Merges nonce + ciphertext, encodes as base64</li>
    </ol>

    <pre class="line-numbers"><code class="language-php">// Internal process (simplified):
$nonce = random_bytes(24);  // Unique per encryption
$ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
    serialize($value),
    '',      // No additional data
    $nonce,
    $this->key
);
return base64_encode($nonce . $ciphertext);</code></pre>

    <h3>Decryption Process</h3>

    <ol>
        <li><strong>Base64 Decode</strong> - Converts base64 string to binary</li>
        <li><strong>Extract Nonce</strong> - First 24 bytes are the nonce</li>
        <li><strong>Extract Ciphertext</strong> - Remaining bytes are encrypted data</li>
        <li><strong>AEAD Decryption</strong> - Decrypts and verifies authentication tag</li>
        <li><strong>Unserialize</strong> - Converts string back to original PHP value</li>
    </ol>

    <h2>Constraint Assertions</h2>

    <p>
        Larafony uses specialized assertion classes following Single Responsibility Principle. Each validates one specific constraint:
    </p>

    <h3>Available Assertions</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Encryption\Assert\EncryptionKeyExists;
use Larafony\Framework\Encryption\Assert\KeyLengthIsValid;
use Larafony\Framework\Encryption\Assert\Base64IsValid;
use Larafony\Framework\Encryption\Assert\DataLengthIsValid;
use Larafony\Framework\Encryption\Assert\DecryptionSucceeded;

// 1. Validates encryption key exists
EncryptionKeyExists::assert($key);
// Throws: RuntimeException if null

// 2. Validates key length (32 bytes for XChaCha20)
KeyLengthIsValid::assert($decodedKey, 32);
// Throws: InvalidArgumentException if wrong length

// 3. Validates base64 decoding
$decoded = base64_decode($encrypted, true);
Base64IsValid::assert($decoded);
// Throws: InvalidArgumentException if invalid base64

// 4. Validates minimum data length
DataLengthIsValid::assert($decoded, 24);
// Throws: InvalidArgumentException if too short

// 5. Validates decryption success
$decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(...);
DecryptionSucceeded::assert($decrypted);
// Throws: InvalidArgumentException if decryption failed</code></pre>

    <h3>PHPStan Support</h3>

    <p>
        Assertion classes include <code>@phpstan-assert</code> annotations for static analysis type narrowing:
    </p>

    <pre class="line-numbers"><code class="language-php">/**
 * @param string|false $decoded
 * @phpstan-assert string $decoded
 */
public static function assert(string|false $decoded): void
{
    if ($decoded === false) {
        throw new InvalidArgumentException('Invalid base64 encoding');
    }
}

// After this assertion, PHPStan knows $decoded is string, not string|false</code></pre>

    <h2>Security Features</h2>

    <h3>Sensitive Parameter Protection</h3>

    <p>
        The <code>encrypt()</code> method uses PHP 8.2+ <code>#[SensitiveParameter]</code> attribute:
    </p>

    <pre class="line-numbers"><code class="language-php">public function encrypt(#[\SensitiveParameter] mixed $value): string
{
    // If exception occurs, $value won't appear in stack traces
}</code></pre>

    <h3>AEAD: Encryption + Authentication</h3>

    <p>
        Unlike older ciphers that require separate HMAC, XChaCha20-Poly1305 provides:
    </p>

    <ul>
        <li><strong>Confidentiality</strong> - Data is encrypted and unreadable</li>
        <li><strong>Authentication</strong> - Detects any tampering with encrypted data</li>
        <li><strong>Performance</strong> - Single operation is faster than encrypt + HMAC</li>
        <li><strong>Timing Safety</strong> - Resistant to timing side-channel attacks</li>
    </ul>

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
                <td>Cipher</td>
                <td><span class="badge bg-success">XChaCha20-Poly1305</span></td>
                <td>AES-256-CBC</td>
                <td>Varies (bundles)</td>
            </tr>
            <tr>
                <td>Library</td>
                <td><span class="badge bg-success">libsodium</span></td>
                <td>OpenSSL</td>
                <td>libsodium (secrets)</td>
            </tr>
            <tr>
                <td>Authentication</td>
                <td><span class="badge bg-success">Built-in (AEAD)</span></td>
                <td>Separate HMAC</td>
                <td>Varies</td>
            </tr>
            <tr>
                <td>Performance</td>
                <td><span class="badge bg-success">Fast (single op)</span></td>
                <td>Slower (two ops)</td>
                <td>Varies</td>
            </tr>
            <tr>
                <td>Timing Attacks</td>
                <td><span class="badge bg-success">Resistant</span></td>
                <td>More vulnerable</td>
                <td>Varies</td>
            </tr>
            <tr>
                <td>Key Management</td>
                <td>Symmetric (APP_KEY)</td>
                <td>Symmetric (APP_KEY)</td>
                <td>Asymmetric (pub/priv)</td>
            </tr>
        </tbody>
    </table>

    <h2>Use Cases</h2>

    <h3>Encrypt Sensitive Configuration</h3>

    <pre class="line-numbers"><code class="language-php">// Store encrypted API credentials
$encryptor = new EncryptionService();

$credentials = [
    'api_key' => 'sk_live_abc123...',
    'api_secret' => 'secret_xyz789...',
    'webhook_secret' => 'whsec_...'
];

$encrypted = $encryptor->encrypt($credentials);

// Store $encrypted in database or config file
// Decrypt when needed:
$decrypted = $encryptor->decrypt($encrypted);</code></pre>

    <h3>Encrypt User Data</h3>

    <pre class="line-numbers"><code class="language-php">// Encrypt personal information before database storage
class User extends Model
{
    public function setSocialSecurityNumber(string $ssn): void
    {
        $encryptor = new EncryptionService();
        $this->ssn_encrypted = $encryptor->encrypt($ssn);
    }

    public function getSocialSecurityNumber(): ?string
    {
        if (!$this->ssn_encrypted) {
            return null;
        }

        $encryptor = new EncryptionService();
        return $encryptor->decrypt($this->ssn_encrypted);
    }
}</code></pre>

    <h2>Best Practices</h2>

    <div class="alert-docs alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>Do's:</strong>
        <ul class="mb-0 mt-2">
            <li>✅ Generate a unique APP_KEY for each environment</li>
            <li>✅ Store APP_KEY in .env file, not in code</li>
            <li>✅ Rotate encryption keys periodically in production</li>
            <li>✅ Use try-catch when decrypting untrusted data</li>
            <li>✅ Encrypt sensitive data before storing in database or cookies</li>
        </ul>
    </div>

    <div class="alert-docs alert-danger">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong>Don'ts:</strong>
        <ul class="mb-0 mt-2">
            <li>❌ Never commit APP_KEY to version control</li>
            <li>❌ Don't share the same APP_KEY across multiple apps</li>
            <li>❌ Don't use encryption for passwords (use hashing instead)</li>
            <li>❌ Don't assume decryption will always succeed</li>
            <li>❌ Don't encrypt data that doesn't need encryption (performance cost)</li>
        </ul>
    </div>

    <h2>Next Steps</h2>

    <ul>
        <li><a href="/docs/session-cookies">Session & Cookie Encryption</a> - See encryption in action with sessions and cookies</li>
        <li><a href="/docs/config">Configuration</a> - Learn how to manage APP_KEY and other settings</li>
        <li><a href="/docs/validation">DTO Validation</a> - Validate data before encryption</li>
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
        <strong>Demo App:</strong> See encryption in action with automatic cookie and session encryption. The demo application showcases XChaCha20-Poly1305 AEAD cipher with constraint-driven validation and PHPStan type narrowing.
        <br><br>
        <a href="https://packagist.org/packages/larafony/skeleton" target="_blank" class="btn btn-sm btn-success me-2">
            <i class="bi bi-box-seam me-1"></i> View on Packagist
        </a>
        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-sm btn-outline-light">
            <i class="bi bi-github me-1"></i> View on GitHub
        </a>
    </div>
</x-docs-layout>
