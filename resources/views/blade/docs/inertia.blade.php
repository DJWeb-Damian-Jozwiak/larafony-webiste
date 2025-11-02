<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Inertia.js & Vue Integration</h1>
    <p class="lead text-white-50">
        Build modern single-page applications using server-side routing with Vue.js components, powered by Inertia.js.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>PSR-15 Middleware:</strong> Inertia.js integration uses PSR-15 middleware for shared props and request handling.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's Inertia.js integration allows you to build SPAs without the complexity of traditional client-side routing:
    </p>
    <ul>
        <li><strong>Server-Side Routing</strong> - Use Larafony's attribute-based routes</li>
        <li><strong>No API Required</strong> - Return data directly from controllers</li>
        <li><strong>Vue 3 Components</strong> - Build UI with modern Vue composition API</li>
        <li><strong>Automatic XHR/HTML Detection</strong> - Seamless initial load and navigation</li>
        <li><strong>Shared Props</strong> - Global data available to all components</li>
        <li><strong>Vite Integration</strong> - Hot module replacement in development</li>
    </ul>

    <h2>Basic Setup</h2>

    <h3>Root View Template</h3>
    <p>Create the Inertia root template at <code>resources/views/blade/inertia.blade.php</code>:</p>

    <pre class="line-numbers"><code class="language-blade">&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="utf-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Larafony App&lt;/title&gt;

    &lt;!-- Vite Assets --&gt;
    {{ "@" }}vite(['resources/js/app.js'])
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Inertia App Container --&gt;
    &lt;div id="app" data-page='&lt;?php echo json_encode($page, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?&gt;'&gt;&lt;/div&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>

    <div class="alert-docs alert-warning">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Important:</strong> The <code>$page</code> variable contains Inertia page data (component, props, url, version) and is automatically passed by the framework.
    </div>

    <h3>Controller with Inertia</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Routing\Advanced\Attributes\RouteParam;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class NotesController extends Controller
{
    #[Route('/notes', 'GET')]
    public function index(): ResponseInterface
    {
        $notes = Note::query()->get();

        // Transform to array for JSON serialization
        $notesData = array_map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'user' => [
                    'name' => $note->user->name ?? 'Unknown',
                ],
            ];
        }, $notes);

        return $this->inertia('Notes/Index', [
            'notes' => $notesData,
        ]);
    }

    #[Route('/notes/&lt;note:\d&gt;', 'GET')]
    #[RouteParam(name: 'note', bind: Note::class)]
    public function show(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        return $this->inertia('Notes/Show', [
            'note' => [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
            ],
        ]);
    }
}</code></pre>

    <h3>Vue Component</h3>
    <p>Create Vue components in <code>resources/js/Pages/</code>:</p>

    <pre class="line-numbers"><code class="language-vue">&lt;!-- resources/js/Pages/Notes/Index.vue --&gt;
&lt;template&gt;
  &lt;div class="container mt-5"&gt;
    &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
      &lt;h1&gt;Notes&lt;/h1&gt;
      &lt;Link href="/notes/create" class="btn btn-primary"&gt;
        Create New Note
      &lt;/Link&gt;
    &lt;/div&gt;

    &lt;div v-if="notes.length === 0" class="alert alert-info"&gt;
      No notes found. Create your first note!
    &lt;/div&gt;

    &lt;div v-else class="row"&gt;
      &lt;div v-for="note in notes" :key="note.id" class="col-md-6 mb-3"&gt;
        &lt;div class="card"&gt;
          &lt;div class="card-body"&gt;
            &lt;h5 class="card-title"&gt;&lbrace;&lbrace; note.title &rbrace;&rbrace;&lt;/h5&gt;
            &lt;p class="card-text"&gt;&lbrace;&lbrace; truncate(note.content, 100) &rbrace;&rbrace;&lt;/p&gt;

            &lt;Link
              :href="`/notes/$&lbrace;note.id&rbrace;`"
              class="btn btn-sm btn-outline-primary"
            &gt;
              View Details
            &lt;/Link&gt;
          &lt;/div&gt;
        &lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/template&gt;

&lt;script setup&gt;
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  notes: Array,
})

const truncate = (text, length) =&gt; {
  if (text.length &lt;= length) return text
  return text.substring(0, length) + '...'
}
&lt;/script&gt;</code></pre>

    <h2>Shared Props with Middleware</h2>
    <p>
        Share global data across all Inertia responses using custom middleware:
    </p>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Http\Middleware\InertiaMiddleware as BaseInertiaMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class AppInertiaMiddleware extends BaseInertiaMiddleware
{
    /**
     * Share data globally with all Inertia responses
     */
    protected function getSharedData(ServerRequestInterface $request): array
    {
        return [
            'auth' => [
                'user' => $this->getAuthenticatedUser($request),
            ],
            // Lazy evaluation - only computed when accessed
            'flash' => fn() => $this->getFlashMessages(),
            'errors' => fn() => $this->getValidationErrors(),
        ];
    }

    private function getAuthenticatedUser(ServerRequestInterface $request): ?array
    {
        // Your authentication logic
        return [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];
    }

    private function getFlashMessages(): array
    {
        // Flash message logic
        return ['success' => 'Operation completed'];
    }

    private function getValidationErrors(): array
    {
        // Validation error logic
        return [];
    }
}</code></pre>

    <h3>Accessing Shared Props in Vue</h3>

    <pre class="line-numbers"><code class="language-vue">&lt;script setup&gt;
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Access shared props
const user = page.props.auth.user
const flash = page.props.flash
&lt;/script&gt;

&lt;template&gt;
  &lt;div v-if="user"&gt;
    Welcome, &lbrace;&lbrace; user.name &rbrace;&rbrace;
  &lt;/div&gt;

  &lt;div v-if="flash.success" class="alert alert-success"&gt;
    &lbrace;&lbrace; flash.success &rbrace;&rbrace;
  &lt;/div&gt;
&lt;/template&gt;</code></pre>

    <h2>Navigation with Inertia</h2>

    <h3>Using Link Component</h3>

    <pre class="line-numbers"><code class="language-vue">&lt;script setup&gt;
import { Link } from '@inertiajs/vue3'
&lt;/script&gt;

&lt;template&gt;
  &lt;!-- Standard navigation --&gt;
  &lt;Link href="/notes"&gt;All Notes&lt;/Link&gt;

  &lt;!-- With HTTP method --&gt;
  &lt;Link href="/notes/1" method="delete" as="button"&gt;
    Delete
  &lt;/Link&gt;

  &lt;!-- Preserve scroll position --&gt;
  &lt;Link href="/notes?page=2" preserve-scroll&gt;
    Next Page
  &lt;/Link&gt;
&lt;/template&gt;</code></pre>

    <h3>Programmatic Navigation</h3>

    <pre class="line-numbers"><code class="language-vue">&lt;script setup&gt;
import { router } from '@inertiajs/vue3'

const deleteNote = (noteId) =&gt; {
  if (confirm('Are you sure?')) {
    router.delete(`/notes/$&lbrace;noteId&rbrace;`, {
      onSuccess: () =&gt; {
        console.log('Note deleted')
      },
      onError: (errors) =&gt; {
        console.error('Failed to delete', errors)
      }
    })
  }
}

const createNote = (data) =&gt; {
  router.post('/notes', data, {
    preserveScroll: true,
    onSuccess: () =&gt; {
      router.visit('/notes')
    }
  })
}
&lt;/script&gt;</code></pre>

    <h2>Vite Configuration</h2>
    <p>
        The <code>{{ "@" }}vite</code> directive automatically handles asset loading:
    </p>

    <pre class="line-numbers"><code class="language-blade">&lt;!-- Development: Loads from Vite dev server (localhost:5173) --&gt;
&lt;!-- Production: Loads from manifest.json --&gt;
{{ "@" }}vite(['resources/js/app.js', 'resources/css/app.css'])</code></pre>

    <h3>Example Vite Entry Point</h3>

    <pre class="line-numbers"><code class="language-javascript">// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp({
  resolve: (name) =&gt; {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/$&lbrace;name&rbrace;.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () =&gt; h(App, props) })
      .use(plugin)
      .mount(el)
  },
})</code></pre>

    <h2>Key Features</h2>

    <h3>Lazy Prop Evaluation</h3>
    <p>Use closures for expensive computations that only run when needed:</p>

    <pre class="line-numbers"><code class="language-php">return $this->inertia('Dashboard', [
    'stats' => [
        'users' => User::count(),
        'posts' => Post::count(),
    ],
    // Only computed on partial reloads requesting 'analytics'
    'analytics' => fn() => $this->computeExpensiveAnalytics(),
]);</code></pre>

    <h3>Partial Reloads</h3>
    <p>Request only specific props to optimize data transfer:</p>

    <pre class="line-numbers"><code class="language-vue">&lt;Link href="/dashboard" only="['stats']"&gt;
  Refresh Stats
&lt;/Link&gt;</code></pre>

    <h3>Asset Versioning</h3>
    <p>Configure asset version for cache invalidation:</p>

    <pre class="line-numbers"><code class="language-php">// In service provider or middleware
$inertia = app(Inertia::class);
$inertia->version(md5_file(public_path('build/manifest.json')));</code></pre>

    <h2>Full Example: CRUD with Relationships</h2>

    <pre class="line-numbers"><code class="language-php">class NotesController extends Controller
{
    #[Route('/notes', 'GET')]
    public function index(): ResponseInterface
    {
        $notes = Note::query()->get();

        return $this->inertia('Notes/Index', [
            'notes' => array_map(fn($note) => [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'user' => [
                    'name' => $note->user->name ?? 'Unknown',
                ],
                'tags' => array_map(
                    fn($tag) => ['id' => $tag->id, 'name' => $tag->name],
                    $note->tags ?? []
                ),
            ], $notes),
        ]);
    }

    #[Route('/notes/&lt;note:\d&gt;', 'GET')]
    #[RouteParam(name: 'note', bind: Note::class)]
    public function show(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        return $this->inertia('Notes/Show', [
            'note' => [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'user' => [
                    'id' => $note->user->id,
                    'name' => $note->user->name,
                ],
                'tags' => array_map(
                    fn($tag) => ['id' => $tag->id, 'name' => $tag->name],
                    $note->tags ?? []
                ),
                'comments' => array_map(
                    fn($c) => [
                        'id' => $c->id,
                        'content' => $c->content,
                        'user' => ['name' => $c->user->name ?? 'Anonymous'],
                    ],
                    $note->comments ?? []
                ),
            ],
        ]);
    }
}</code></pre>

    <div class="mt-5 p-4" style="background: rgba(139, 92, 246, 0.1); border-left: 4px solid var(--primary);">
        <h4 class="gradient-text mb-3">Key Differences from Laravel</h4>
        <ul class="mb-0">
            <li><strong>PSR-15 Middleware:</strong> Larafony uses standard PSR-15 middleware instead of Laravel's proprietary middleware</li>
            <li><strong>Attribute-Based Routing:</strong> Routes defined with PHP attributes on controller methods, not in route files</li>
            <li><strong>No Magic Directives:</strong> Uses standard PHP for <code>$page</code> serialization instead of custom Blade directives</li>
            <li><strong>Type-Safe:</strong> Full PHP 8.5 type hints and model binding with <code>#[RouteParam]</code></li>
            <li><strong>Built-in Feature:</strong> Inertia.js integration is part of the framework core, not a separate package</li>
        </ul>
    </div>

    <div class="alert-docs alert-success mt-4">
        <i class="bi bi-rocket-takeoff-fill me-2"></i>
        <strong>Demo App:</strong> See the full Inertia.js implementation with CRUD operations, relationships, and Vue components in action.
        <br><br>
        <a href="https://packagist.org/packages/larafony/skeleton" target="_blank" class="btn btn-sm btn-success me-2">
            <i class="bi bi-box-seam me-1"></i> View on Packagist
        </a>
        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-sm btn-outline-light">
            <i class="bi bi-github me-1"></i> View on GitHub
        </a>
    </div>
</x-docs-layout>
