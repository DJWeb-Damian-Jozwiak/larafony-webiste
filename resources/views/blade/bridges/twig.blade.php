<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Twig Template Bridge</h1>
    <p class="lead text-white-50">
        Use Twig templating engine as an alternative to Blade.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/view-twig</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\View\Twig\ServiceProviders\TwigServiceProvider;

$app->withServiceProviders([
    TwigServiceProvider::class
]);</code></pre>

    <h2>Template Example</h2>
    <pre class="line-numbers"><code class="language-twig">&lbrace;# resources/views/twig/welcome.twig #&rbrace;
&lbrace;% extends "layout.twig" %&rbrace;

&lbrace;% block title %&rbrace;Welcome&lbrace;% endblock %&rbrace;

&lbrace;% block content %&rbrace;
    &lt;h1&gt;Hello &lbrace;&lbrace; name &rbrace;&rbrace;!&lt;/h1&gt;

    &lbrace;% if user.isAdmin %&rbrace;
        &lt;p&gt;You have admin access.&lt;/p&gt;
    &lbrace;% endif %&rbrace;

    &lt;ul&gt;
    &lbrace;% for item in items %&rbrace;
        &lt;li&gt;&lbrace;&lbrace; item.name &rbrace;&rbrace; - &lbrace;&lbrace; item.price|number_format(2) &rbrace;&rbrace;&lt;/li&gt;
    &lbrace;% endfor %&rbrace;
    &lt;/ul&gt;
&lbrace;% endblock %&rbrace;</code></pre>

    <h2>Usage in Controller</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\View\Twig\TwigRenderer;

final class HomeController extends Controller
{
    #[Route('/', methods: ['GET'])]
    public function index(TwigRenderer $twig): ResponseInterface
    {
        $html = $twig->render('welcome.twig', [
            'name' => 'John',
            'items' => Item::all(),
        ]);

        return new Response(200, [], $html);
    }
}</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Template inheritance</strong> - extends, block, parent</li>
        <li><strong>Filters</strong> - date, number_format, escape, etc.</li>
        <li><strong>Control structures</strong> - if, for, include</li>
        <li><strong>Macros</strong> - Reusable template functions</li>
        <li><strong>Auto-escaping</strong> - XSS protection by default</li>
    </ul>
</x-bridges-layout>
