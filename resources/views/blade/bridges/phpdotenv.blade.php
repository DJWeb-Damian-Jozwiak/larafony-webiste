<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">PHP dotenv Bridge</h1>
    <p class="lead text-white-50">
        Load environment variables from .env files using vlucas/phpdotenv.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/env-phpdotenv</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\Env\Phpdotenv\ServiceProviders\PhpdotenvServiceProvider;

$app->withServiceProviders([
    PhpdotenvServiceProvider::class
]);</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Variable expansion</strong> - <code>${BASE_URL}/api</code> expands correctly</li>
        <li><strong>Multiline values</strong> - Support for multiline strings</li>
        <li><strong>Comments</strong> - Lines starting with <code>#</code> are ignored</li>
        <li><strong>Type casting</strong> - Boolean and null values</li>
        <li><strong>Validation</strong> - Required variables enforcement</li>
    </ul>

    <h2>Example .env</h2>
    <pre class="line-numbers"><code class="language-bash"># Application
APP_NAME="My Larafony App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

# Database
DB_HOST=localhost
DB_DATABASE=larafony
DB_USERNAME=root
DB_PASSWORD=secret

# Variable expansion
API_URL="${APP_URL}/api/v1"

# Multiline value
RSA_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA...
-----END RSA PRIVATE KEY-----"</code></pre>
</x-bridges-layout>
