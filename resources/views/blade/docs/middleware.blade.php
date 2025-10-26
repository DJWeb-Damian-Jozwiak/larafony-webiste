<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Middleware</h1>
    <p class="lead text-white-50">
        Create PSR-15 compliant middleware to process requests and responses
    </p>

    <h2>What is Middleware?</h2>
    <p>
        Middleware provides a convenient mechanism for filtering HTTP requests entering your application.
        Larafony uses PSR-15 middleware, making it compatible with any PSR-15 compliant middleware.
    </p>

    <h2>Creating Middleware</h2>
    <p>
        Implement the <code>MiddlewareInterface</code> from PSR-15:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogRequestMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Before request
        $start = microtime(true);

        // Process request
        $response = $handler->handle($request);

        // After request
        $duration = microtime(true) - $start;
        error_log("Request to {$request->getUri()} took {$duration}s");

        return $response;
    }
}</code></pre>

    <h2>Attaching Middleware to Routes</h2>
    <p>
        Use the <code>#[Middleware]</code> attribute to attach middleware to specific routes:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use Larafony\Framework\Routing\Advanced\Attributes\{Route, Middleware};
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class AdminController extends Controller
{
    #[Route('/admin/dashboard', 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function dashboard(): ResponseInterface
    {
        // Only accessible if AuthMiddleware passes

        return $this->render('admin.dashboard');
    }
}</code></pre>

    <h2>Authentication Middleware Example</h2>
    <p>
        Here's a complete authentication middleware:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Larafony\Framework\Http\Response\RedirectResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Check if user is authenticated
        $session = $request->getAttribute('session');

        if (!$session || !$session->has('user_id')) {
            // Not authenticated - redirect to login
            return new RedirectResponse('/login');
        }

        // Authenticated - continue
        return $handler->handle($request);
    }
}</code></pre>

    <h2>CORS Middleware Example</h2>
    <p>
        Add CORS headers to API responses:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return new Response(200, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        }

        // Process request
        $response = $handler->handle($request);

        // Add CORS headers
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}</code></pre>

    <h2>Request Transformation</h2>
    <p>
        Middleware can modify the request before it reaches the controller:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AddUserToRequestMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $session = $request->getAttribute('session');

        if ($session && $session->has('user_id')) {
            // Load user from database
            $user = User::query()->find($session->get('user_id'));

            // Add user to request attributes
            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }
}</code></pre>

    <p>Access the user in your controller:</p>

    <pre class="line-numbers"><code class="language-php">#[Route('/profile', 'GET')]
#[Middleware(AddUserToRequestMiddleware::class)]
public function profile(ServerRequestInterface $request): ResponseInterface
{
    $user = $request->getAttribute('user');

    return $this->render('profile', ['user' => $user]);
}</code></pre>

    <h2>Multiple Middleware</h2>
    <p>
        Stack multiple middleware on a single route:
    </p>

    <pre class="line-numbers"><code class="language-php">#[Route('/admin/users', 'GET')]
#[Middleware(AuthMiddleware::class)]
#[Middleware(AdminMiddleware::class)]
#[Middleware(LogRequestMiddleware::class)]
public function index(): ResponseInterface
{
    // Protected by three middleware layers

    return $this->render('admin.users');
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Execution Order:</strong> Middleware executes in the order listed.
        In this example: Auth → Admin → LogRequest → Controller → LogRequest → Admin → Auth
    </div>

    <h2>JSON API Middleware</h2>
    <p>
        Ensure all responses are JSON:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class JsonResponseMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);

        // Ensure Content-Type is application/json
        if (!$response->hasHeader('Content-Type')) {
            $response = $response->withHeader('Content-Type', 'application/json');
        }

        return $response;
    }
}</code></pre>

    <h2>Global Middleware</h2>
    <p>
        Register middleware globally in <code>bootstrap/app.php</code>:
    </p>

    <pre class="line-numbers"><code class="language-php">$app->withMiddleware([
    LogRequestMiddleware::class,
    CorsMiddleware::class,
]);</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>Tip:</strong> Use global middleware for cross-cutting concerns like logging and CORS.
        Use route-specific middleware for authorization and role checks.
    </div>

    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/controllers">Learn about using middleware with controllers →</a></li>
        <li><a href="/docs/bootstrap">Learn about configuring global middleware →</a></li>
    </ul>
</x-docs-layout>
