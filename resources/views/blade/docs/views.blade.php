<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Views & Blade Templates</h1>
    <p class="lead text-white-50">
        Build dynamic views with Larafony's Blade-inspired template engine featuring components, layouts, and directives.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>PSR-7 Integration:</strong> Views extend PSR-7 Response, making them directly returnable from controllers.
    </div>

    <h2>Overview</h2>
    <p>
        Larafony's template system provides:
    </p>
    <ul>
        <li><strong>Blade Syntax</strong> - Familiar directives like {{ "@" }}if, {{ "@" }}foreach, {{ "@" }}extends</li>
        <li><strong>Component System</strong> - Reusable UI components with slots</li>
        <li><strong>Template Inheritance</strong> - Layouts and sections</li>
        <li><strong>Compiled Templates</strong> - Cached for performance</li>
        <li><strong>PSR-7 Compatible</strong> - Return directly from controllers</li>
    </ul>

    <h2>Basic View Usage</h2>

    <h3>Returning Views from Controllers</h3>

    <pre class="line-numbers"><code class="language-php">use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;

class HomeController extends Controller
{
    public function index(): ResponseInterface
    {
        // Renders resources/views/home.blade.php
        return $this->render('home', [
            'title' => 'Welcome',
            'user' => $user,
        ]);
    }
}</code></pre>

    <h3>View File Structure</h3>

    <pre class="line-numbers"><code class="language-bash">resources/
└── views/
    └── blade/
        ├── home.blade.php
        ├── layouts/
        │   └── app.blade.php
        ├── components/
        │   ├── alert.blade.php
        │   └── card.blade.php
        └── posts/
            ├── index.blade.php
            └── show.blade.php</code></pre>

    <h2>Blade Syntax</h2>

    <h3>Displaying Data</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- Escaped output (safe) --}}
{{ $name }}
{{ $user->email }}

{{-- Raw output (use with caution) --}}
{!! $htmlContent !!}

{{-- Comments (won't appear in HTML) --}}
{{-- This is a comment --}}</code></pre>

    <h3>Control Structures</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- If statements --}}
{{ "@" }}if($user->isAdmin())
    <p>Welcome, Admin!</p>
{{ "@" }}elseif($user->isModerator())
    <p>Welcome, Moderator!</p>
{{ "@" }}else
    <p>Welcome, User!</p>
{{ "@" }}endif

{{-- Unless (inverse if) --}}
{{ "@" }}unless($user->isBanned())
    <p>You can post comments</p>
{{ "@" }}endunless

{{-- Isset check --}}
{{ "@" }}isset($user)
    <p>User: {{ $user->name }}</p>
{{ "@" }}endisset

{{-- Empty check --}}
{{ "@" }}empty($posts)
    <p>No posts found</p>
{{ "@" }}endempty</code></pre>

    <h3>Loops</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- Foreach loop --}}
{{ "@" }}foreach($posts as $post)
    <article>
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->excerpt }}</p>
    </article>
{{ "@" }}endforeach

{{-- For loop --}}
{{ "@" }}for($i = 0; $i < 10; $i++)
    <p>Item {{ $i }}</p>
{{ "@" }}endfor

{{-- While loop --}}
{{ "@" }}while($condition)
    <p>Processing...</p>
{{ "@" }}endwhile</code></pre>

    <h2>Layouts & Sections</h2>

    <h3>Defining a Layout</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Larafony' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    {{ "@" }}stack('styles')
</head>
<body>
    <header>
        <h1>{{ $title }}</h1>
        <nav>
            <a href="/">Home</a>
            <a href="/about">About</a>
        </nav>
    </header>

    <main>
        {{ "@" }}yield('content')
    </main>

    <footer>
        <p>&copy; 2025 Larafony</p>
    </footer>

    {{ "@" }}stack('scripts')
</body>
</html></code></pre>

    <h3>Extending a Layout</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/posts/show.blade.php --}}
{{ "@" }}extend('layouts.app')

{{ "@" }}section('content')
    <article>
        <h2>{{ $post->title }}</h2>
        <p class="meta">By {{ $post->author }} on {{ $post->created_at }}</p>

        <div class="content">
            {!! $post->content !!}
        </div>

        {{ "@" }}if($post->tags)
            <div class="tags">
                {{ "@" }}foreach($post->tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                {{ "@" }}endforeach
            </div>
        {{ "@" }}endif
    </article>
{{ "@" }}endsection

{{ "@" }}push('scripts')
    <script src="/js/post-interactions.js"></script>
{{ "@" }}endpush</code></pre>

    <h2>Components (The Power of Larafony)</h2>

    <h3>Creating a Component Class</h3>

    <pre class="line-numbers"><code class="language-php">&lt;?php
// app/View/Components/Alert.php

namespace App\View\Components;

use Larafony\Framework\View\Component;

class Alert extends Component
{
    public function __construct(
        public readonly string $type = 'info',
        public readonly bool $dismissible = false,
        public readonly ?string $title = null,
    ) {}

    protected function getView(): string
    {
        return 'components.alert';
    }
}</code></pre>

    <h3>Component Template</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/components/alert.blade.php --}}
<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible' : '' }}">
    {{ "@" }}if($dismissible)
        <button type="button" class="close">&times;</button>
    {{ "@" }}endif

    {{ "@" }}if($title)
        <h4 class="alert-title">{{ $title }}</h4>
    {{ "@" }}endif

    <div class="alert-content">
        {!! $slot !!}
    </div>
</div></code></pre>

    <h3>Using Components</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- Simple usage --}}
<x-alert type="success">
    Operation completed successfully!
</x-alert>

{{-- With attributes --}}
<x-alert type="warning" :dismissible="true" title="Warning">
    Please review your settings.
</x-alert>

{{-- Dynamic attributes --}}
<x-alert :type="$messageType" :title="$messageTitle">
    {{ $messageContent }}
</x-alert></code></pre>

    <h2>Component Slots</h2>

    <h3>Named Slots</h3>

    <pre class="line-numbers"><code class="language-php">// app/View/Components/Card.php
class Card extends Component
{
    public function __construct(
        public readonly ?string $title = null,
    ) {}

    protected function getView(): string
    {
        return 'components.card';
    }
}</code></pre>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/components/card.blade.php --}}
<div class="card">
    {{ "@" }}isset($slots['header'])
        <div class="card-header">
            {!! $slots['header'] !!}
        </div>
    {{ "@" }}endisset

    <div class="card-body">
        {{ "@" }}if($title)
            <h3 class="card-title">{{ $title }}</h3>
        {{ "@" }}endif

        {!! $slot !!}
    </div>

    {{ "@" }}isset($slots['footer'])
        <div class="card-footer">
            {!! $slots['footer'] !!}
        </div>
    {{ "@" }}endisset
</div></code></pre>

    <h3>Using Named Slots</h3>

    <pre class="line-numbers"><code class="language-blade"><x-card title="User Profile">
    <x-slot:header>
        <img src="{{ $user->avatar }}" alt="Avatar">
    </x-slot:header>

    <p>Name: {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>

    <x-slot:footer>
        <button>Edit Profile</button>
    </x-slot:footer>
</x-card></code></pre>

    <h2>Advanced Component Examples</h2>

    <h3>Example 1: Button Component</h3>

    <pre class="line-numbers"><code class="language-php">// app/View/Components/Button.php
class Button extends Component
{
    public function __construct(
        public readonly string $type = 'button',
        public readonly string $variant = 'primary',
        public readonly bool $disabled = false,
    ) {}

    protected function getView(): string
    {
        return 'components.button';
    }
}</code></pre>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/components/button.blade.php --}}
<button
    type="{{ $type }}"
    class="btn btn-{{ $variant }}"
    {{ $disabled ? 'disabled' : '' }}
>
    {!! $slot !!}
</button>

{{-- Usage --}}
<x-button type="submit" variant="success">
    Save Changes
</x-button>

<x-button variant="danger" :disabled="true">
    Delete
</x-button></code></pre>

    <h3>Example 2: Modal Component</h3>

    <pre class="line-numbers"><code class="language-php">// app/View/Components/Modal.php
class Modal extends Component
{
    public function __construct(
        public readonly string $id,
        public readonly string $size = 'md',
    ) {}

    protected function getView(): string
    {
        return 'components.modal';
    }
}</code></pre>

    <pre class="line-numbers"><code class="language-blade">{{-- resources/views/components/modal.blade.php --}}
<div class="modal" id="{{ $id }}">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            {{ "@" }}isset($slots['header'])
                <div class="modal-header">
                    {!! $slots['header'] !!}
                    <button class="close">&times;</button>
                </div>
            {{ "@" }}endisset

            <div class="modal-body">
                {!! $slot !!}
            </div>

            {{ "@" }}isset($slots['footer'])
                <div class="modal-footer">
                    {!! $slots['footer'] !!}
                </div>
            {{ "@" }}endisset
        </div>
    </div>
</div>

{{-- Usage --}}
<x-modal id="confirmDelete" size="sm">
    <x-slot:header>
        <h5>Confirm Deletion</h5>
    </x-slot:header>

    Are you sure you want to delete this item?

    <x-slot:footer>
        <x-button variant="secondary">Cancel</x-button>
        <x-button variant="danger">Delete</x-button>
    </x-slot:footer>
</x-modal></code></pre>

    <h2>Asset Stacks</h2>

    <h3>Pushing to Stacks</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- In any view --}}
{{ "@" }}push('styles')
    <link rel="stylesheet" href="/css/custom.css">
{{ "@" }}endpush

{{ "@" }}push('scripts')
    <script src="/js/charts.js"></script>
    <script>
        initCharts();
    </script>
{{ "@" }}endpush</code></pre>

    <h3>Rendering Stacks</h3>

    <pre class="line-numbers"><code class="language-blade">{{-- In layout --}}
<head>
    <link rel="stylesheet" href="/css/app.css">
    {{ "@" }}stack('styles')
</head>
<body>
    {{-- Content --}}

    <script src="/js/app.js"></script>
    {{ "@" }}stack('scripts')
</body></code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Use  {!! '{{ }}' !!} for displaying data (automatic escaping)</li>
            <li>Create reusable components for repeated UI patterns</li>
            <li>Use named slots for complex component layouts</li>
            <li>Keep component logic in the component class</li>
            <li>Use layouts for consistent page structure</li>
            <li>Push scripts to stacks for proper loading order</li>
        </ul>
    </div>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-x-circle text-danger me-2"></i>Don't</h4>
        <ul class="mb-0">
            <li>Don't use {!! '{!! !!}' !!} for user input (XSS risk)</li>
            <li>Don't put business logic in views</li>
            <li>Don't create deeply nested components (3+ levels)</li>
            <li>Don't forget to escape user-provided content</li>
        </ul>
    </div>

    <h2>Security</h2>

    <div class="alert-docs alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>XSS Protection:</strong> Always use {{ "{{ }}" }} for user input. Only use {!! !!} for trusted content.
    </div>

    <pre class="line-numbers"><code class="language-blade">{{-- SAFE - Automatically escaped --}}
<p>Welcome, {{ $user->name }}</p>

{{-- DANGEROUS - Use only for trusted HTML --}}
<div>{!! $trustedHtmlContent !!}</div>

{{-- WRONG - Vulnerable to XSS --}}
<div>{!! $userComment !!}</div>

{{-- CORRECT --}}
<div>{{ $userComment }}</div></code></pre>

    <h2>Next Steps</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-signpost-2 me-2 text-primary"></i>Controllers</h4>
                <p class="text-white-50 mb-3">Learn how to create controllers and return views with data.</p>
                <a href="/docs/controllers" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-shield-check me-2 text-primary"></i>Validation</h4>
                <p class="text-white-50 mb-3">Validate user input with DTO validation.</p>
                <a href="/docs/validation" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
