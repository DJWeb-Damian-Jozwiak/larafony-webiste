<x-docs-layout :title="$title" :description="$description">
    <div class="alert alert-info mb-4" style="border-left: 4px solid #6366f1; background: rgba(99, 102, 241, 0.15); padding: 1.5rem; border-radius: 0.5rem;">
        <h4 style="color: #818cf8; margin-bottom: 0.75rem;">
            <i class="bi bi-star-fill me-2"></i>🎯 Probably the Only One!
        </h4>
        <p style="color: #cbd5e1; margin-bottom: 0;" class="lead">
            <strong>The only PHP framework with native, built-from-scratch interactive error debugging in the console!</strong>
            <br><br>
            While other frameworks like Laravel rely on external tools like PsySH for interactive console debugging,
            Larafony implements its own sophisticated debug session system. When an exception occurs in your console commands,
            you don't just get a stack trace and exit—you get an <strong>interactive REPL-like experience</strong> where you can
            explore frames, inspect variables, view source code, and understand the error context without leaving your terminal
            or installing external dependencies.
            <br><br>
            This is the same beautiful, web-like debugging experience you get in browsers... <strong>but available in your console! 😎</strong>
        </p>
    </div>

    <h1 class="gradient-text">Console Error Debugging</h1>

    <p class="lead text-white-50">
        Interactive, REPL-like debugging experience when exceptions occur in CLI commands
    </p>

    <div class="mb-4">
        <span class="badge bg-primary"><i class="bi bi-star-fill me-1"></i>Native Implementation</span>
        <span class="badge bg-success"><i class="bi bi-terminal me-1"></i>Interactive Session</span>
        <span class="badge bg-info"><i class="bi bi-code-square me-1"></i>Frame Inspection</span>
        <span class="badge bg-warning text-dark"><i class="bi bi-eye me-1"></i>Variable Explorer</span>
        <span class="badge bg-danger"><i class="bi bi-file-code me-1"></i>Source Viewer</span>
    </div>

    <h2>What Makes This Unique?</h2>

    <div class="alert-docs alert-success">
        <strong>🚀 Native, Built-in Implementation</strong>
        <p class="mb-0">
            Unlike other frameworks that rely on external packages like PsySH, Larafony's console debugger is
            built from scratch and deeply integrated into the framework. Zero external dependencies, zero configuration,
            just pure framework magic!
        </p>
    </div>

    <h2>Key Features</h2>

    <ul>
        <li><strong>Interactive Debug Session</strong> - Drop into a REPL-like interface when exceptions occur</li>
        <li><strong>Command-Based Exploration</strong> - Type commands to explore stack traces, frames, variables, and source code</li>
        <li><strong>Automatic Environment Detection</strong> - Detects CLI vs web mode via <code>php_sapi_name()</code></li>
        <li><strong>Beautiful Console Output</strong> - Color-coded, syntax-highlighted display</li>
        <li><strong>Frame Navigation</strong> - Jump between stack frames to understand execution flow</li>
        <li><strong>Variable Inspection</strong> - View all variables available in each frame</li>
        <li><strong>Source Code Display</strong> - See code snippets with context around errors</li>
        <li><strong>Fatal Error Recovery</strong> - Catches shutdown errors (E_ERROR, E_PARSE, E_CORE_ERROR, etc.)</li>
    </ul>

    <h2>How It Works</h2>

    <h3>Automatic Registration</h3>

    <p>
        The ErrorHandlerServiceProvider automatically detects if your application is running in CLI mode
        and registers the appropriate handler. No configuration needed!
    </p>

    <pre class="line-numbers"><code class="language-php">// In ErrorHandlerServiceProvider::register()
$isConsole = php_sapi_name() === 'cli';

if ($isConsole) {
    // Register console error handler with factory
    $factory = new ConsoleRendererFactory($container);
    $renderer = $factory->create();

    $handler = new ConsoleHandler(
        $renderer,
        fn(int $exitCode) => exit($exitCode)
    );

    $container->set(BaseHandler::class, $handler);
} else {
    // Register web error handler
    $handler = new DetailedErrorHandler($viewManager, $debug);
    $container->set(BaseHandler::class, $handler);
}</code></pre>

    <h3>Service Provider Order</h3>

    <div class="alert-docs alert-warning">
        <strong>⚠️ Important:</strong>
        ConsoleServiceProvider must be registered <strong>before</strong> ErrorHandlerServiceProvider
        because it provides the OutputContract dependency.
    </div>

    <pre class="line-numbers"><code class="language-php">// In bootstrap/console.php
$app->withServiceProviders([
    HttpServiceProvider::class,
    ConfigServiceProvider::class,
    ConsoleServiceProvider::class,    // ← Must come first
    DatabaseServiceProvider::class,
    ErrorHandlerServiceProvider::class, // ← Depends on ConsoleServiceProvider
]);</code></pre>

    <h2>Interactive Debug Commands</h2>

    <p>When an exception occurs, you'll see a prompt where you can type commands:</p>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Command</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>help</code></td>
                <td>Show all available commands</td>
            </tr>
            <tr>
                <td><code>trace</code></td>
                <td>Display full stack trace</td>
            </tr>
            <tr>
                <td><code>frame N</code></td>
                <td>Inspect frame number N in detail</td>
            </tr>
            <tr>
                <td><code>vars</code></td>
                <td>Show variables in current frame</td>
            </tr>
            <tr>
                <td><code>source</code></td>
                <td>Display source code around error</td>
            </tr>
            <tr>
                <td><code>env</code></td>
                <td>Show environment information (PHP version, memory, etc.)</td>
            </tr>
            <tr>
                <td><code>exit</code></td>
                <td>Exit debug session</td>
            </tr>
        </tbody>
    </table>

    <h2>Example Debug Session</h2>

    <p>Here's what you see when an exception occurs in a console command:</p>

    <pre class="line-numbers"><code class="language-bash">$ php bin/larafony process:data

Processing data...

[ERROR] RuntimeException
Data processing failed: Invalid format

in /var/www/app/Commands/ProcessDataCommand.php:24

Debug mode: (type 'help' for available commands) > help

Available commands:
  trace    - Show full stack trace
  frame N  - Show details for frame N
  vars     - Show variables in current frame
  source   - Show source code around error
  env      - Show environment information
  exit     - Exit debug mode

Debug mode: (type 'help' for available commands) > trace

Stack trace:
  #0 ProcessDataCommand->processComplexData()
     at /var/www/app/Commands/ProcessDataCommand.php:24

  #1 ProcessDataCommand->handle()
     at /var/www/app/Commands/ProcessDataCommand.php:15

  #2 Kernel->handleCommand()
     at /var/www/framework/src/Console/Kernel.php:42

Debug mode: (type 'help' for available commands) > frame 0

Frame #0: ProcessDataCommand->processComplexData()
File: /var/www/app/Commands/ProcessDataCommand.php:24

Source code:
  20 │
  21 │     private function processComplexData(): array
  22 │     {
  23 │         // This will trigger the console error handler
> 24 │         throw new \RuntimeException('Data processing failed: Invalid format');
  25 │     }
  26 │ }

Variables:
  $this = ProcessDataCommand {#42}
  $output = Output {#12}

Debug mode: (type 'help' for available commands) > env

Environment Information:
  PHP Version: 8.5.0
  Memory Usage: 4.2 MB / 512 MB
  Execution Time: 0.15s
  Operating System: Linux
  SAPI: cli

Debug mode: (type 'help' for available commands) > exit</code></pre>

    <h2>Architecture</h2>

    <h3>Core Components</h3>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Component</th>
                <th>Purpose</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>BaseHandler</code></td>
                <td>Abstract base for all error handlers (web + console)</td>
            </tr>
            <tr>
                <td><code>ConsoleHandler</code></td>
                <td>CLI-specific error handler</td>
            </tr>
            <tr>
                <td><code>ConsoleRenderer</code></td>
                <td>Orchestrates rendering of exception details</td>
            </tr>
            <tr>
                <td><code>DebugSession</code></td>
                <td>Interactive command loop (REPL)</td>
            </tr>
            <tr>
                <td><code>ConsoleRendererFactory</code></td>
                <td>Constructs renderer with all dependencies</td>
            </tr>
        </tbody>
    </table>

    <h3>BaseHandler Abstract Class</h3>

    <p>Provides common error handling functionality:</p>

    <pre class="line-numbers"><code class="language-php">abstract class BaseHandler
{
    // Register PHP error/exception handlers
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleFatalError']);
    }

    // Convert PHP errors to exceptions
    public function handleError(int $level, string $message, ...): bool
    {
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    // Catch fatal errors during shutdown
    public function handleFatalError(): void
    {
        $error = error_get_last();
        if ($error !== null && $this->isFatalError($error['type'])) {
            $this->handleException(new FatalError(...));
        }
    }

    abstract public function handleException(Throwable $exception): void;
}</code></pre>

    <h3>Fatal Error Types</h3>

    <p>The handler catches these fatal errors:</p>

    <ul>
        <li><code>E_ERROR</code> - Fatal run-time errors</li>
        <li><code>E_PARSE</code> - Compile-time parse errors</li>
        <li><code>E_CORE_ERROR</code> - Fatal errors during PHP's initial startup</li>
        <li><code>E_COMPILE_ERROR</code> - Fatal compile-time errors</li>
        <li><code>E_USER_ERROR</code> - User-generated error message</li>
        <li><code>E_USER_DEPRECATED</code> - User-generated deprecation notice</li>
    </ul>

    <h2>Comparison with Other Solutions</h2>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Feature</th>
                <th>Larafony</th>
                <th>Laravel (Tinker + PsySH)</th>
                <th>Symfony 7.4</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Interactive Debugging</strong></td>
                <td><span class="badge bg-success">Built-in</span></td>
                <td><span class="badge bg-warning text-dark">External (PsySH)</span></td>
                <td><span class="badge bg-danger">Not Available</span></td>
            </tr>
            <tr>
                <td><strong>Dependencies</strong></td>
                <td>Zero</td>
                <td>Requires psy/psysh</td>
                <td>symfony/error-handler</td>
            </tr>
            <tr>
                <td><strong>Installation</strong></td>
                <td>Automatic</td>
                <td>composer require laravel/tinker</td>
                <td>Automatic</td>
            </tr>
            <tr>
                <td><strong>Frame Navigation</strong></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-x-circle-fill text-danger"></i></td>
            </tr>
            <tr>
                <td><strong>Variable Inspection</strong></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-x-circle-fill text-danger"></i></td>
            </tr>
            <tr>
                <td><strong>Source Code View</strong></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
            </tr>
            <tr>
                <td><strong>Clean Terminal Output</strong></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i></td>
                <td><i class="bi bi-check-circle-fill text-success"></i> (Since 7.4)</td>
            </tr>
            <tr>
                <td><strong>Trigger</strong></td>
                <td>On Exception</td>
                <td>Manual (tinker command)</td>
                <td>On Exception</td>
            </tr>
        </tbody>
    </table>

    <div class="alert-docs alert-info">
        <strong>💡 Key Difference:</strong>
        <p class="mb-0">
            Larafony's interactive debugger activates automatically when exceptions occur, without requiring
            external tools or manual invocation. Laravel's Tinker is primarily a REPL for exploring your application,
            not an automatic exception handler. Symfony 7.4 improved console error display but doesn't offer interactive debugging.
        </p>
    </div>

    <h2>Creating a Test Command</h2>

    <p>Try it yourself by creating a simple command that throws an exception:</p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Commands;

use Larafony\Framework\Console\Attributes\Command;
use Larafony\Framework\Console\Contracts\CommandContract;
use Larafony\Framework\Console\Contracts\OutputContract;

#[Command(name: 'test:error', description: 'Test console error handler')]
class TestErrorCommand implements CommandContract
{
    public function __construct(private OutputContract $output)
    {
    }

    public function handle(array $options = []): int
    {
        $this->output->info('About to trigger an exception...');

        // This will activate the interactive debugger
        throw new \RuntimeException('Test exception for debugging!');
    }
}</code></pre>

    <p>Run it with:</p>

    <pre class="line-numbers"><code class="language-bash">php bin/larafony test:error</code></pre>

    <p>You'll immediately drop into the interactive debug session!</p>

    <h2>Best Practices</h2>

    <ul>
        <li><strong>Development Only</strong> - The interactive debugger is perfect for development. In production, exceptions should be logged</li>
        <li><strong>Explore Thoroughly</strong> - Use <code>trace</code> to see the full picture, then <code>frame N</code> to dive deep</li>
        <li><strong>Check Variables</strong> - Always inspect variables with <code>vars</code> to understand state</li>
        <li><strong>Exit Cleanly</strong> - Type <code>exit</code> to quit, or Ctrl+C for immediate termination</li>
        <li><strong>Use for Learning</strong> - Great way to understand framework internals and execution flow</li>
    </ul>

    <h2>Future Enhancements</h2>

    <p>Planned improvements for the console debugger:</p>

    <ul>
        <li>Code execution in frame context (eval)</li>
        <li>Search within stack traces</li>
        <li>Export debug session to file</li>
        <li>Custom command extensions</li>
        <li>Integration with logging</li>
    </ul>

    <div class="alert-docs alert-success">
        <strong>🎉 Conclusion</strong>
        <p class="mb-0">
            Larafony's console debugger represents a significant innovation in PHP framework error handling.
            By building this functionality from scratch and integrating it deeply into the framework, we've created
            a debugging experience that's both powerful and developer-friendly. No external dependencies, no complex
            setup—just pure, interactive debugging magic! 😎
        </p>
    </div>
</x-docs-layout>
