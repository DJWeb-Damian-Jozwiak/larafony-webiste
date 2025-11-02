<x-docs-layout :title="$title" :description="$description" >
    <h1>Error Handling</h1>
    
    <p class="lead">
        Comprehensive error handling system with beautiful debug views and production-ready error pages.
    </p>
    
    <div class="mb-4">
        <span class="badge-tag"><i class="bi bi-check-circle-fill me-1"></i>PSR-3 Ready</span>
        <span class="badge-tag"><i class="bi bi-check-circle-fill me-1"></i>Blade Templates</span>
        <span class="badge-tag"><i class="bi bi-check-circle-fill me-1"></i>Debug Mode</span>
        <span class="badge-tag"><i class="bi bi-check-circle-fill me-1"></i>Production Safe</span>
    </div>

    <h2>Overview</h2>
    
    <p>
        The Larafony error handling system captures all uncaught exceptions and PHP errors, rendering them through 
        beautiful Blade templates. It automatically switches between detailed debug views for development and 
        clean, user-friendly error pages for production based on the <code>APP_DEBUG</code> environment variable.
    </p>

    <h2>Key Features</h2>
    
    <ul>
        <li><strong>Native PHP Exception Handlers</strong> - Uses <code>set_exception_handler()</code> and <code>set_error_handler()</code> for reliable error capture</li>
        <li><strong>Interactive Debug View</strong> - VS Code-inspired dark theme with clickable stack frames and code snippets</li>
        <li><strong>Environment Awareness</strong> - Automatically detects <code>APP_DEBUG</code> to show appropriate views</li>
        <li><strong>Status Code Detection</strong> - Distinguishes between 404 (NotFoundError) and 500 (other exceptions)</li>
        <li><strong>Graceful Fallback</strong> - Provides plain HTML if Blade rendering fails</li>
        <li><strong>Fatal Error Handling</strong> - Catches fatal errors through shutdown functions</li>
    </ul>

    <h2>Configuration</h2>
    
    <h3>Environment Setup</h3>
    
    <p>Control error display mode through your <code>.env</code> file:</p>
    
    <pre class="line-numbers"><code class="language-bash"># Development: Show detailed debug traces
APP_DEBUG=true

# Production: Show user-friendly error pages
APP_DEBUG=false</code></pre>

    <h3>Service Provider Registration</h3>
    
    <p>
        Register the ErrorHandlerServiceProvider in your <code>bootstrap/app.php</code>. 
        <strong>Important:</strong> It must be registered AFTER ViewServiceProvider because it depends on ViewManager.
    </p>
    
    <pre class="line-numbers"><code class="language-php">$app->withServiceProviders([
    ConfigServiceProvider::class,
    DatabaseServiceProvider::class,
    HttpServiceProvider::class,
    RouteServiceProvider::class,
    ViewServiceProvider::class,  // ViewManager must be registered first
    WebServiceProvider::class,
    ErrorHandlerServiceProvider::class, // Must be LAST
]);</code></pre>

    <h2>Error Views</h2>
    
    <p>Create three Blade views in <code>resources/views/blade/errors/</code>:</p>

    <h3>404 Error Page (errors.404)</h3>
    
    <p>Rendered when <code>NotFoundError</code> exception is thrown:</p>
    
    <pre class="line-numbers"><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;title&gt;404 - Page Not Found&lt;/title&gt;
    &lt;style&gt;
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Beautiful purple gradient with animated stars */
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;404 - Page Not Found&lt;/h1&gt;
    &lt;p&gt;The page you're looking for doesn't exist.&lt;/p&gt;
    &lt;a href="/"&gt;Go Home&lt;/a&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

    <h3>500 Error Page (errors.500)</h3>
    
    <p>Rendered for all other exceptions in production mode:</p>
    
    <pre class="line-numbers"><code class="language-html">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;title&gt;500 - Internal Server Error&lt;/title&gt;
    &lt;style&gt;
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Matching blue gradient theme */
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;h1&gt;500 - Internal Server Error&lt;/h1&gt;
    &lt;p&gt;Something went wrong on our end.&lt;/p&gt;
    &lt;a href="/"&gt;Go Home&lt;/a&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

    <h3>Debug View (errors.debug)</h3>
    
    <p>Rendered in debug mode with full exception details and interactive backtrace:</p>
    
    <pre class="line-numbers"><code class="language-php">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;title&gt;&#123;&#123; $exception['class'] &#125;&#125; | Debug&lt;/title&gt;
    &lt;!-- VS Code dark theme styling --&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Exception details --&gt;
    &lt;div class="header"&gt;
        &lt;div class="exception-class"&gt;&#123;&#123; $exception['class'] &#125;&#125;&lt;/div&gt;
        &lt;div class="exception-message"&gt;&#123;&#123; $exception['message'] &#125;&#125;&lt;/div&gt;
        &lt;div class="exception-location"&gt;
            &#123;&#123; $exception['file'] &#125;&#125;:&#123;&#123; $exception['line'] &#125;&#125;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Sidebar with clickable stack frames --&gt;
    &lt;div class="sidebar"&gt;
        &#64;foreach($backtrace as $index =&gt; $frame)
        &lt;div class="frame" data-frame="&#123;&#123; $index &#125;&#125;"&gt;
            &#123;&#123; $frame['class'] &#125;&#125;::&#123;&#123; $frame['function'] &#125;&#125;()
            &lt;br&gt;&#123;&#123; $frame['file'] &#125;&#125;:&#123;&#123; $frame['line'] &#125;&#125;
        &lt;/div&gt;
        &#64;endforeach
    &lt;/div&gt;

    &lt;!-- Code snippets with line numbers --&gt;
    &lt;div class="content"&gt;
        &#64;foreach($backtrace as $index =&gt; $frame)
        &lt;div id="frame-&#123;&#123; $index &#125;&#125;"&gt;
            &#64;foreach($frame['snippet']['lines'] as $lineNum =&gt; $lineContent)
            &lt;div class="code-line &#123;&#123; $lineNum == $frame['snippet']['errorLine'] ? 'error-line' : '' &#125;&#125;"&gt;
                &lt;span class="line-number"&gt;&#123;&#123; $lineNum &#125;&#125;&lt;/span&gt;
                &lt;span class="line-content"&gt;&#123;&#123; $lineContent &#125;&#125;&lt;/span&gt;
            &lt;/div&gt;
            &#64;endforeach
        &lt;/div&gt;
        &#64;endforeach
    &lt;/div&gt;

    &lt;!-- JavaScript for frame navigation --&gt;
    &lt;script&gt;
        document.querySelectorAll('.frame').forEach(frame =&gt; &#123;
            frame.addEventListener('click', function() &#123;
                // Switch to selected frame's code
            &#125;);
        &#125;);
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

    <h2>Usage Examples</h2>

    <h3>Throwing 404 Errors</h3>
    
    <p>Throw <code>NotFoundError</code> to trigger a 404 response:</p>
    
    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Core\Exceptions\NotFoundError;

public function findForRoute(string|int $value): static
&#123;
    $result = self::query()-&gt;where('id', '=', $value)-&gt;first();

    if ($result === null) &#123;
        throw new NotFoundError(
            sprintf('Model %s with id %s not found', static::class, $value)
        );
    &#125;

    return $result;
&#125;</code></pre>

    <h3>Custom Exception Handling</h3>
    
    <p>Create custom exceptions extending NotFoundError:</p>
    
    <pre class="line-numbers"><code class="language-php">namespace App\Exceptions;

use Larafony\Framework\Core\Exceptions\NotFoundError;

class ResourceNotFoundException extends NotFoundError
&#123;
    public function __construct(string $resource, string|int $id)
    &#123;
        parent::__construct(
            sprintf('Resource "%s" with ID "%s" was not found', $resource, $id)
        );
    &#125;
&#125;

// Usage
throw new ResourceNotFoundException('User', 42);</code></pre>

    <h3>Programmatic Backtrace Generation</h3>
    
    <p>Generate backtraces programmatically for logging or debugging:</p>
    
    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\ErrorHandler\Backtrace;

$backtrace = new Backtrace();

try &#123;
    // Risky operation
    throw new \RuntimeException('Something went wrong');
&#125; catch (\Throwable $e) &#123;
    $trace = $backtrace-&gt;generate($e);

    foreach ($trace-&gt;frames as $frame) &#123;
        echo $frame-&gt;file . ':' . $frame-&gt;line . PHP_EOL;
        echo $frame-&gt;class . '::' . $frame-&gt;function . '()' . PHP_EOL;

        // Access code snippet
        foreach ($frame-&gt;snippet-&gt;lines as $lineNum =&gt; $lineContent) &#123;
            echo $lineNum . ': ' . $lineContent . PHP_EOL;
        &#125;
    &#125;
&#125;</code></pre>

    <h2>Architecture</h2>

    <h3>Core Components</h3>
    
    <ul>
        <li><code>DetailedErrorHandler</code> - Main error handler implementing ErrorHandler contract</li>
        <li><code>Backtrace</code> - Factory for generating TraceCollection from exceptions</li>
        <li><code>TraceCollection</code> - Immutable collection of TraceFrame objects</li>
        <li><code>TraceFrame</code> - Readonly value object representing a stack frame</li>
        <li><code>CodeSnippet</code> - Extracts code context around error lines</li>
        <li><code>ErrorHandlerServiceProvider</code> - Integrates error handler into service container</li>
    </ul>

    <h3>Modern PHP Features</h3>
    
    <p>The error handling system leverages PHP 8.5 features:</p>
    
    <pre class="line-numbers"><code class="language-php">// Property hooks for immutable public access
class TraceCollection
&#123;
    public private(set) array $frames;  // Public read, private write
&#125;

// Readonly value objects
readonly class TraceFrame
&#123;
    public function __construct(
        public string $file,
        public int $line,
        public ?string $class,
        public string $function,
        public array $args,
        public CodeSnippet $snippet
    ) &#123;&#125;
&#125;</code></pre>

    <h2>Comparison with Other Frameworks</h2>

    <table class="table table-dark table-bordered mt-4">
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
                <td><strong>Registration</strong></td>
                <td><code>set_exception_handler()</code></td>
                <td><code>withExceptions()</code> in bootstrap</td>
                <td>Event listener on <code>kernel.exception</code></td>
            </tr>
            <tr>
                <td><strong>Debug Views</strong></td>
                <td>Custom Blade templates</td>
                <td>Whoops library</td>
                <td>Symfony Profiler</td>
            </tr>
            <tr>
                <td><strong>Configuration</strong></td>
                <td>ENV (<code>APP_DEBUG</code>)</td>
                <td>Config files + ENV</td>
                <td>YAML/PHP config</td>
            </tr>
            <tr>
                <td><strong>Approach</strong></td>
                <td>Native PHP handlers + Blade</td>
                <td>Laravel exceptions + middleware</td>
                <td>Event-driven architecture</td>
            </tr>
        </tbody>
    </table>

    <div class="alert alert-info mt-4">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Key Difference:</strong> Larafony uses PHP's native exception handlers directly for simplicity and transparency, 
        while Laravel and Symfony use framework-specific abstractions. This makes Larafony's error handling easier to understand 
        and customize without deep framework knowledge.
    </div>

    <h2>Learn More</h2>
    
    <p>
        This implementation is explained in detail with step-by-step tutorials, tests, and best practices at 
        <a href="https://masterphp.eu" target="_blank" class="text-decoration-none" style="color: #ec4899;">
            <strong>masterphp.eu</strong> <i class="bi bi-arrow-up-right-square"></i>
        </a>
    </p>

    <div class="alert-docs alert-success mt-4">
        <i class="bi bi-rocket-takeoff-fill me-2"></i>
        <strong>Demo App:</strong> See error handling in action with custom 404/500 pages and interactive debug views. The demo application showcases production-ready error templates with beautiful gradients and full exception backtraces in development mode.
        <br><br>
        <a href="https://packagist.org/packages/larafony/skeleton" target="_blank" class="btn btn-sm btn-success me-2">
            <i class="bi bi-box-seam me-1"></i> View on Packagist
        </a>
        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-sm btn-outline-light">
            <i class="bi bi-github me-1"></i> View on GitHub
        </a>
    </div>
</x-docs-layout>
