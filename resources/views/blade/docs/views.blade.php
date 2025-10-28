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

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- Escaped output (safe) --&rbrace;&rbrace;
&lbrace;&lbrace; $name &rbrace;&rbrace;
&lbrace;&lbrace; $user->email &rbrace;&rbrace;

&lbrace;&lbrace;-- Raw output (use with caution) --&rbrace;&rbrace;
&lbrace;!! $htmlContent !!&rbrace;

&lbrace;&lbrace;-- Comments (won't appear in HTML) --&rbrace;&rbrace;
&lbrace;&lbrace;-- This is a comment --&rbrace;&rbrace;</code></pre>

    <h3>Control Structures</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- If statements --&rbrace;&rbrace;
{{ "@" }}if($user->isAdmin())
    &lt;p&gt;Welcome, Admin!&lt;/p&gt;
{{ "@" }}elseif($user->isModerator())
    &lt;p&gt;Welcome, Moderator!&lt;/p&gt;
{{ "@" }}else
    &lt;p&gt;Welcome, User!&lt;/p&gt;
{{ "@" }}endif

&lbrace;&lbrace;-- Unless (inverse if) --&rbrace;&rbrace;
{{ "@" }}unless($user->isBanned())
    &lt;p&gt;You can post comments&lt;/p&gt;
{{ "@" }}endunless

&lbrace;&lbrace;-- Isset check --&rbrace;&rbrace;
{{ "@" }}isset($user)
    &lt;p&gt;User: &lbrace;&lbrace; $user->name &rbrace;&rbrace;&lt;/p&gt;
{{ "@" }}endisset

&lbrace;&lbrace;-- Empty check --&rbrace;&rbrace;
{{ "@" }}empty($posts)
    &lt;p&gt;No posts found&lt;/p&gt;
{{ "@" }}endempty</code></pre>

    <h3>Loops</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- Foreach loop --&rbrace;&rbrace;
{{ "@" }}foreach($posts as $post)
    &lt;article&gt;
        &lt;h2&gt;&lbrace;&lbrace; $post->title &rbrace;&rbrace;&lt;/h2&gt;
        &lt;p&gt;&lbrace;&lbrace; $post->excerpt &rbrace;&rbrace;&lt;/p&gt;
    &lt;/article&gt;
{{ "@" }}endforeach

&lbrace;&lbrace;-- For loop --&rbrace;&rbrace;
{{ "@" }}for($i = 0; $i &lt; 10; $i++)
    &lt;p&gt;Item &lbrace;&lbrace; $i &rbrace;&rbrace;&lt;/p&gt;
{{ "@" }}endfor

&lbrace;&lbrace;-- While loop --&rbrace;&rbrace;
{{ "@" }}while($condition)
    &lt;p&gt;Processing...&lt;/p&gt;
{{ "@" }}endwhile</code></pre>

    <h2>Layouts & Sections</h2>

    <h3>Defining a Layout</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/layouts/app.blade.php --&rbrace;&rbrace;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;&lbrace;&lbrace; $title ?? 'Larafony' &rbrace;&rbrace;&lt;/title&gt;
    &lt;link rel="stylesheet" href="/css/app.css"&gt;
    {{ "@" }}stack('styles')
&lt;/head&gt;
&lt;body&gt;
    &lt;header&gt;
        &lt;h1&gt;&lbrace;&lbrace; $title &rbrace;&rbrace;&lt;/h1&gt;
        &lt;nav&gt;
            &lt;a href="/"&gt;Home&lt;/a&gt;
            &lt;a href="/about"&gt;About&lt;/a&gt;
        &lt;/nav&gt;
    &lt;/header&gt;

    &lt;main&gt;
        {{ "@" }}yield('content')
    &lt;/main&gt;

    &lt;footer&gt;
        &lt;p&gt;&amp;copy; 2025 Larafony&lt;/p&gt;
    &lt;/footer&gt;

    {{ "@" }}stack('scripts')
&lt;/body&gt;
&lt;/html&gt;</code></pre>

    <h3>Extending a Layout</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/posts/show.blade.php --&rbrace;&rbrace;
{{ "@" }}extend('layouts.app')

{{ "@" }}section('content')
    &lt;article&gt;
        &lt;h2&gt;&lbrace;&lbrace; $post->title &rbrace;&rbrace;&lt;/h2&gt;
        &lt;p class="meta"&gt;By &lbrace;&lbrace; $post->author &rbrace;&rbrace; on &lbrace;&lbrace; $post->created_at &rbrace;&rbrace;&lt;/p&gt;

        &lt;div class="content"&gt;
            &lbrace;!! $post->content !!&rbrace;
        &lt;/div&gt;

        {{ "@" }}if($post->tags)
            &lt;div class="tags"&gt;
                {{ "@" }}foreach($post->tags as $tag)
                    &lt;span class="tag"&gt;&lbrace;&lbrace; $tag &rbrace;&rbrace;&lt;/span&gt;
                {{ "@" }}endforeach
            &lt;/div&gt;
        {{ "@" }}endif
    &lt;/article&gt;
{{ "@" }}endsection

{{ "@" }}push('scripts')
    &lt;script src="/js/post-interactions.js"&gt;&lt;/script&gt;
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

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/components/alert.blade.php --&rbrace;&rbrace;
&lt;div class="alert alert-&lbrace;&lbrace; $type &rbrace;&rbrace; &lbrace;&lbrace; $dismissible ? 'alert-dismissible' : '' &rbrace;&rbrace;"&gt;
    {{ "@" }}if($dismissible)
        &lt;button type="button" class="close"&gt;&amp;times;&lt;/button&gt;
    {{ "@" }}endif

    {{ "@" }}if($title)
        &lt;h4 class="alert-title"&gt;&lbrace;&lbrace; $title &rbrace;&rbrace;&lt;/h4&gt;
    {{ "@" }}endif

    &lt;div class="alert-content"&gt;
        &lbrace;!! $slot !!&rbrace;
    &lt;/div&gt;
&lt;/div&gt;</code></pre>

    <h3>Using Components</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- Simple usage --&rbrace;&rbrace;
&lt;x-alert type="success"&gt;
    Operation completed successfully!
&lt;/x-alert&gt;

&lbrace;&lbrace;-- With attributes --&rbrace;&rbrace;
&lt;x-alert type="warning" :dismissible="true" title="Warning"&gt;
    Please review your settings.
&lt;/x-alert&gt;

&lbrace;&lbrace;-- Dynamic attributes --&rbrace;&rbrace;
&lt;x-alert :type="$messageType" :title="$messageTitle"&gt;
    &lbrace;&lbrace; $messageContent &rbrace;&rbrace;
&lt;/x-alert&gt;</code></pre>

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

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/components/card.blade.php --&rbrace;&rbrace;
&lt;div class="card"&gt;
    {{ "@" }}isset($slots['header'])
        &lt;div class="card-header"&gt;
            &lbrace;!! $slots['header'] !!&rbrace;
        &lt;/div&gt;
    {{ "@" }}endisset

    &lt;div class="card-body"&gt;
        {{ "@" }}if($title)
            &lt;h3 class="card-title"&gt;&lbrace;&lbrace; $title &rbrace;&rbrace;&lt;/h3&gt;
        {{ "@" }}endif

        &lbrace;!! $slot !!&rbrace;
    &lt;/div&gt;

    {{ "@" }}isset($slots['footer'])
        &lt;div class="card-footer"&gt;
            &lbrace;!! $slots['footer'] !!&rbrace;
        &lt;/div&gt;
    {{ "@" }}endisset
&lt;/div&gt;</code></pre>

    <h3>Using Named Slots</h3>

    <pre class="line-numbers"><code class="language-blade">&lt;x-card title="User Profile"&gt;
    &lt;x-slot:header&gt;
        &lt;img src="&lbrace;&lbrace; $user->avatar &rbrace;&rbrace;" alt="Avatar"&gt;
    &lt;/x-slot:header&gt;

    &lt;p&gt;Name: &lbrace;&lbrace; $user->name &rbrace;&rbrace;&lt;/p&gt;
    &lt;p&gt;Email: &lbrace;&lbrace; $user->email &rbrace;&rbrace;&lt;/p&gt;

    &lt;x-slot:footer&gt;
        &lt;button&gt;Edit Profile&lt;/button&gt;
    &lt;/x-slot:footer&gt;
&lt;/x-card&gt;</code></pre>

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

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/components/button.blade.php --&rbrace;&rbrace;
&lt;button
    type="&lbrace;&lbrace; $type &rbrace;&rbrace;"
    class="btn btn-&lbrace;&lbrace; $variant &rbrace;&rbrace;"
    &lbrace;&lbrace; $disabled ? 'disabled' : '' &rbrace;&rbrace;
&gt;
    &lbrace;!! $slot !!&rbrace;
&lt;/button&gt;

&lbrace;&lbrace;-- Usage --&rbrace;&rbrace;
&lt;x-button type="submit" variant="success"&gt;
    Save Changes
&lt;/x-button&gt;

&lt;x-button variant="danger" :disabled="true"&gt;
    Delete
&lt;/x-button&gt;</code></pre>

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

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- resources/views/components/modal.blade.php --&rbrace;&rbrace;
&lt;div class="modal" id="&lbrace;&lbrace; $id &rbrace;&rbrace;"&gt;
    &lt;div class="modal-dialog modal-&lbrace;&lbrace; $size &rbrace;&rbrace;"&gt;
        &lt;div class="modal-content"&gt;
            {{ "@" }}isset($slots['header'])
                &lt;div class="modal-header"&gt;
                    &lbrace;!! $slots['header'] !!&rbrace;
                    &lt;button class="close"&gt;&amp;times;&lt;/button&gt;
                &lt;/div&gt;
            {{ "@" }}endisset

            &lt;div class="modal-body"&gt;
                &lbrace;!! $slot !!&rbrace;
            &lt;/div&gt;

            {{ "@" }}isset($slots['footer'])
                &lt;div class="modal-footer"&gt;
                    &lbrace;!! $slots['footer'] !!&rbrace;
                &lt;/div&gt;
            {{ "@" }}endisset
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;

&lbrace;&lbrace;-- Usage --&rbrace;&rbrace;
&lt;x-modal id="confirmDelete" size="sm"&gt;
    &lt;x-slot:header&gt;
        &lt;h5&gt;Confirm Deletion&lt;/h5&gt;
    &lt;/x-slot:header&gt;

    Are you sure you want to delete this item?

    &lt;x-slot:footer&gt;
        &lt;x-button variant="secondary"&gt;Cancel&lt;/x-button&gt;
        &lt;x-button variant="danger"&gt;Delete&lt;/x-button&gt;
    &lt;/x-slot:footer&gt;
&lt;/x-modal&gt;</code></pre>

    <h2>Asset Stacks</h2>

    <h3>Pushing to Stacks</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- In any view --&rbrace;&rbrace;
{{ "@" }}push('styles')
    &lt;link rel="stylesheet" href="/css/custom.css"&gt;
{{ "@" }}endpush

{{ "@" }}push('scripts')
    &lt;script src="/js/charts.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        initCharts();
    &lt;/script&gt;
{{ "@" }}endpush</code></pre>

    <h3>Rendering Stacks</h3>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- In layout --&rbrace;&rbrace;
&lt;head&gt;
    &lt;link rel="stylesheet" href="/css/app.css"&gt;
    {{ "@" }}stack('styles')
&lt;/head&gt;
&lt;body&gt;
    &lbrace;&lbrace;-- Content --&rbrace;&rbrace;

    &lt;script src="/js/app.js"&gt;&lt;/script&gt;
    {{ "@" }}stack('scripts')
&lt;/body&gt;</code></pre>

    <h2>Best Practices</h2>

    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
        <h4><i class="bi bi-check-circle text-success me-2"></i>Do</h4>
        <ul class="mb-0">
            <li>Use  <code>&lbrace;&lbrace; &rbrace;&rbrace;</code> for displaying data (automatic escaping)</li>
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
            <li>Don't use <code>&lbrace;!! !!&rbrace;</code> for user input (XSS risk)</li>
            <li>Don't put business logic in views</li>
            <li>Don't create deeply nested components (3+ levels)</li>
            <li>Don't forget to escape user-provided content</li>
        </ul>
    </div>

    <h2>Security</h2>

    <div class="alert-docs alert-danger">
        <i class="bi bi-shield-exclamation me-2"></i>
        <strong>XSS Protection:</strong> Always use <code>&lbrace;&lbrace; &rbrace;&rbrace;</code> for user input. Only use <code>&lbrace;!! !!&rbrace;</code> for trusted content.
    </div>

    <pre class="line-numbers"><code class="language-blade">&lbrace;&lbrace;-- SAFE - Automatically escaped --&rbrace;&rbrace;
&lt;p&gt;Welcome, &lbrace;&lbrace; $user->name &rbrace;&rbrace;&lt;/p&gt;

&lbrace;&lbrace;-- DANGEROUS - Use only for trusted HTML --&rbrace;&rbrace;
&lt;div&gt;&lbrace;!! $trustedHtmlContent !!&rbrace;&lt;/div&gt;

&lbrace;&lbrace;-- WRONG - Vulnerable to XSS --&rbrace;&rbrace;
&lt;div&gt;&lbrace;!! $userComment !!&rbrace;&lt;/div&gt;

&lbrace;&lbrace;-- CORRECT --&rbrace;&rbrace;
&lt;div&gt;&lbrace;&lbrace; $userComment &rbrace;&rbrace;&lt;/div&gt;</code></pre>

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
