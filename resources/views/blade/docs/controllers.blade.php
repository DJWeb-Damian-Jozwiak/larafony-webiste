<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Controllers & Routing</h1>
    <p class="lead text-white-50">
        Define routes with PHP attributes and create RESTful controllers
    </p>

    <h2>Creating a Controller</h2>
    <p>
        Controllers in Larafony extend the <code>Controller</code> base class and use the <code>#[Route]</code> attribute
        to define routes directly on methods.
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Controllers;

use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomeController extends Controller
{
    #[Route('/', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('home', [
            'title' => 'Welcome to Larafony'
        ]);
    }
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Auto-Discovery:</strong> Routes are automatically discovered from the <code>src/Controllers</code> directory.
        No need to manually register routes!
    </div>

    <h2>Route Attributes</h2>
    <p>
        The <code>#[Route]</code> attribute accepts a path and HTTP method:
    </p>

    <pre class="line-numbers"><code class="language-php">#[Route('/notes', 'GET')]
public function index(): ResponseInterface
{
    // GET /notes
}

#[Route('/notes', 'POST')]
public function store(): ResponseInterface
{
    // POST /notes
}

#[Route('/notes/{id}', 'GET')]
public function show(int $id): ResponseInterface
{
    // GET /notes/123
}

#[Route('/notes/{id}', 'PUT')]
public function update(int $id): ResponseInterface
{
    // PUT /notes/123
}

#[Route('/notes/{id}', 'DELETE')]
public function destroy(int $id): ResponseInterface
{
    // DELETE /notes/123
}</code></pre>

    <h2>Route Parameters</h2>
    <p>
        Capture route parameters using curly braces in the path:
    </p>

    <pre class="line-numbers"><code class="language-php">#[Route('/users/{id}', 'GET')]
public function show(int $id): ResponseInterface
{
    $user = User::query()->find($id);

    return $this->render('users.show', ['user' => $user]);
}

#[Route('/posts/{slug}', 'GET')]
public function showBySlug(string $slug): ResponseInterface
{
    $post = Post::query()
        ->where('slug', '=', $slug)
        ->first();

    return $this->render('posts.show', ['post' => $post]);
}</code></pre>

    <h2>Model Binding (Auto-Binding)</h2>
    <p>
        One of Larafony's most powerful features is automatic model binding. Using the <code>#[RouteParam]</code> attribute,
        you can automatically resolve route parameters into model instances. The framework will fetch the model from the database
        and inject it directly into your controller method.
    </p>

    <h3>Basic Model Binding</h3>
    <p>
        Use <code>#[RouteParam]</code> to configure model binding:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Models\Note;
use Larafony\Framework\Routing\Advanced\Attributes\{Route, RouteParam};

#[Route('/notes/&lt;note&gt;', 'GET')]
#[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
public function show(ServerRequestInterface $request, Note $note): ResponseInterface
{
    // $note is automatically loaded from database
    // using the route parameter value

    return $this->render('notes.show', ['note' => $note]);
}</code></pre>

    <div class="alert-docs alert-success">
        <i class="bi bi-lightbulb-fill me-2"></i>
        <strong>How it works:</strong> When you visit <code>/notes/123</code>, Larafony:
        <ol class="mt-2 mb-0">
            <li>Validates the parameter matches the pattern (<code>\d+</code>)</li>
            <li>Calls <code>Note::findForRoute(123)</code> to load the model</li>
            <li>Injects the loaded model into your controller method</li>
            <li>Returns 404 automatically if the model is not found!</li>
        </ol>
    </div>

    <h3>Model findForRoute Method</h3>
    <p>
        Your model must implement the <code>findForRoute()</code> method for binding to work:
    </p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Database\ORM\Model;

class Note extends Model
{
    public string $table { get => 'notes'; }

    public static function findForRoute(int|string $id): ?static
    {
        return static::query()->find($id);
    }
}</code></pre>

    <h3>Before and After Comparison</h3>
    <p>
        See how model binding simplifies your code:
    </p>

    <pre class="line-numbers"><code class="language-php">// ❌ Without Model Binding (manual approach)
#[Route('/notes/&lt;id:\d+&gt;', 'GET')]
public function show(ServerRequestInterface $request): ResponseInterface
{
    $params = $request->getAttribute('routeParams');
    $note = Note::query()->find($params['id']);

    if (!$note) {
        // Handle 404
        return new Response(404, [], 'Note not found');
    }

    return $this->render('notes.show', ['note' => $note]);
}

// ✅ With Model Binding (automatic approach)
#[Route('/notes/&lt;note&gt;', 'GET')]
#[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
public function show(ServerRequestInterface $request, Note $note): ResponseInterface
{
    // $note is already loaded, 404 handled automatically!

    return $this->render('notes.show', ['note' => $note]);
}</code></pre>

    <h3>Custom Resolution Methods</h3>
    <p>
        Use <code>findMethod</code> parameter to specify a custom resolution method (e.g., find by slug instead of ID):
    </p>

    <pre class="line-numbers"><code class="language-php">// In your model
class Post extends Model
{
    public static function findBySlug(string $slug): ?static
    {
        return static::query()->where('slug', '=', $slug)->first();
    }
}

// In your controller
#[Route('/posts/&lt;slug:[a-z0-9-]+&gt;', 'GET')]
#[RouteParam(name: 'slug', bind: Post::class, findMethod: 'findBySlug')]
public function show(ServerRequestInterface $request, Post $post): ResponseInterface
{
    // $post resolved via findBySlug() instead of default findForRoute()
    // Pattern validation ([a-z0-9-]+) and binding in the same attribute!

    return $this->render('posts.show', ['post' => $post]);
}</code></pre>

    <h3>Multiple Model Bindings</h3>
    <p>
        You can bind multiple models in a single route:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Models\User;
use App\Models\Note;

#[Route('/users/&lt;user&gt;/notes/&lt;note&gt;', 'GET')]
#[RouteParam(name: 'user', pattern: '\d+', bind: User::class)]
#[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
public function showUserNote(
    ServerRequestInterface $request,
    User $user,
    Note $note
): ResponseInterface {
    // Both models are automatically loaded!
    // $user is loaded from &lt;user&gt; parameter
    // $note is loaded from &lt;note&gt; parameter

    return $this->render('users.notes.show', [
        'user' => $user,
        'note' => $note
    ]);
}</code></pre>

    <h3>Combining Model Binding with DTOs</h3>
    <p>
        Mix model binding with DTO injection for powerful update operations:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Models\Note;
use App\DTOs\UpdateNoteDto;

#[Route('/notes/&lt;note&gt;', 'PUT')]
#[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
public function update(
    ServerRequestInterface $request,
    Note $note,
    UpdateNoteDto $dto
): ResponseInterface {
    // $note is auto-bound from route parameter
    // $dto is auto-created and validated from request body

    $note->title = $dto->title;
    $note->content = $dto->content;
    $note->save();

    return $this->redirect("/notes/{$note->id}");
}</code></pre>

    <h3>Working with Relationships</h3>
    <p>
        Auto-bound models work seamlessly with relationships:
    </p>

    <pre class="line-numbers"><code class="language-php">#[Route('/notes/&lt;note&gt;', 'GET')]
#[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
public function show(ServerRequestInterface $request, Note $note): ResponseInterface
{
    // Access relationships directly
    $author = $note->user;
    $tags = $note->tags;
    $comments = $note->comments;

    return $this->render('notes.show', [
        'note' => $note,
        'author' => $author,
        'tags' => $tags,
        'comments' => $comments
    ]);
}</code></pre>

    <h3>Complete CRUD with Model Binding</h3>
    <p>
        Here's a complete CRUD controller using model binding:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\Models\Note;
use App\DTOs\{CreateNoteDto, UpdateNoteDto};
use Larafony\Framework\Routing\Advanced\Attributes\{Route, RouteParam};

class NoteController extends Controller
{
    // List all notes (no binding needed)
    #[Route('/notes', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $notes = Note::query()->get();
        return $this->render('notes.index', ['notes' => $notes]);
    }

    // Show single note (model binding)
    #[Route('/notes/&lt;note&gt;', 'GET')]
    #[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
    public function show(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        return $this->render('notes.show', ['note' => $note]);
    }

    // Show edit form (model binding)
    #[Route('/notes/&lt;note&gt;/edit', 'GET')]
    #[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
    public function edit(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        return $this->render('notes.edit', ['note' => $note]);
    }

    // Update note (model binding + DTO)
    #[Route('/notes/&lt;note&gt;', 'PUT')]
    #[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
    public function update(
        ServerRequestInterface $request,
        Note $note,
        UpdateNoteDto $dto
    ): ResponseInterface {
        $note->title = $dto->title;
        $note->content = $dto->content;
        $note->save();

        return $this->redirect("/notes/{$note->id}");
    }

    // Delete note (model binding)
    #[Route('/notes/&lt;note&gt;', 'DELETE')]
    #[RouteParam(name: 'note', pattern: '\d+', bind: Note::class)]
    public function destroy(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        $note->delete();
        return $this->redirect('/notes');
    }
}</code></pre>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Route Parameter Syntax:</strong> Larafony uses <code>&lt;param&gt;</code> or <code>&lt;param:pattern&gt;</code>
        syntax for route parameters, not <code>{param}</code>. This allows inline regex patterns like
        <code>&lt;id:\d+&gt;</code> or <code>&lt;slug:[a-z0-9-]+&gt;</code>.
    </div>

    <h2>DTO Injection</h2>
    <p>
        Type-hint a DTO class to automatically validate and hydrate request data:
    </p>

    <pre class="line-numbers"><code class="language-php">use App\DTOs\CreateNoteDto;

#[Route('/notes', 'POST')]
public function store(CreateNoteDto $dto): ResponseInterface
{
    // $dto is automatically created from request
    // and validated based on attributes

    $note = new Note()->fill([
        'title' => $dto->title,
        'content' => $dto->content,
    ]);
    $note->save();

    return $this->redirect('/notes');
}</code></pre>

    <p>See the <a href="/docs/validation">DTO Validation</a> guide for more details.</p>

    <h2>Response Helpers</h2>
    <p>
        The <code>Controller</code> base class provides helpful methods for creating responses:
    </p>

    <h3>Rendering Views</h3>
    <pre class="line-numbers"><code class="language-php">// Render a Blade template
return $this->render('notes.index', [
    'notes' => $notes
]);</code></pre>

    <h3>JSON Responses</h3>
    <pre class="line-numbers"><code class="language-php">// Return JSON
return $this->json([
    'success' => true,
    'data' => $notes
]);

// With status code
return $this->json(['error' => 'Not found'], 404);</code></pre>

    <h3>Redirects</h3>
    <pre class="line-numbers"><code class="language-php">// Redirect to a URL
return $this->redirect('/notes');

// Redirect with status code
return $this->redirect('/login', 302);</code></pre>

    <h2>Complete CRUD Example</h2>
    <p>
        Here's a complete RESTful controller for managing notes:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Note;
use App\DTOs\CreateNoteDto;
use App\DTOs\UpdateNoteDto;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class NoteController extends Controller
{
    #[Route('/notes', 'GET')]
    public function index(): ResponseInterface
    {
        $notes = Note::query()->get();

        return $this->render('notes.index', ['notes' => $notes]);
    }

    #[Route('/notes/create', 'GET')]
    public function create(): ResponseInterface
    {
        return $this->render('notes.create');
    }

    #[Route('/notes', 'POST')]
    public function store(CreateNoteDto $dto): ResponseInterface
    {
        $note = new Note()->fill([
            'title' => $dto->title,
            'content' => $dto->content,
            'user_id' => 1, // Get from auth
        ]);
        $note->save();

        return $this->redirect('/notes');
    }

    #[Route('/notes/{id}', 'GET')]
    public function show(Note $note): ResponseInterface
    {
        return $this->render('notes.show', ['note' => $note]);
    }

    #[Route('/notes/{id}/edit', 'GET')]
    public function edit(Note $note): ResponseInterface
    {
        return $this->render('notes.edit', ['note' => $note]);
    }

    #[Route('/notes/{id}', 'PUT')]
    public function update(Note $note, UpdateNoteDto $dto): ResponseInterface
    {
        $note->title = $dto->title;
        $note->content = $dto->content;
        $note->save();

        return $this->redirect("/notes/{$note->id}");
    }

    #[Route('/notes/{id}', 'DELETE')]
    public function destroy(Note $note): ResponseInterface
    {
        $note->delete();

        return $this->redirect('/notes');
    }
}</code></pre>

    <h2>API Controllers</h2>
    <p>
        Create JSON APIs by returning JSON responses:
    </p>

    <pre class="line-numbers"><code class="language-php">&lt;?php

namespace App\Controllers;

use App\Models\Note;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class ApiNoteController extends Controller
{
    #[Route('/api/notes', 'GET')]
    public function index(): ResponseInterface
    {
        $notes = Note::query()->get();

        return $this->json([
            'success' => true,
            'data' => $notes
        ]);
    }

    #[Route('/api/notes/{id}', 'GET')]
    public function show(Note $note): ResponseInterface
    {
        return $this->json([
            'success' => true,
            'data' => $note
        ]);
    }
}</code></pre>

    <h2>Next Steps</h2>
    <ul>
        <li><a href="/docs/validation">Learn about DTO validation →</a></li>
        <li><a href="/docs/middleware">Learn about adding middleware to routes →</a></li>
        <li><a href="/docs/models">Learn about using models →</a></li>
    </ul>
</x-docs-layout>
