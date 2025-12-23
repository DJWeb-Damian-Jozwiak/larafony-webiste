<x-bridges-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Smarty Template Bridge</h1>
    <p class="lead text-white-50">
        Use Smarty templating engine as an alternative to Blade.
    </p>

    <h2>Installation</h2>
    <pre class="line-numbers"><code class="language-bash">composer require larafony/view-smarty</code></pre>

    <h2>Configuration</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\View\Smarty\ServiceProviders\SmartyServiceProvider;

$app->withServiceProviders([
    SmartyServiceProvider::class
]);</code></pre>

    <h2>Template Example</h2>
    <pre class="line-numbers"><code class="language-smarty">&lbrace;* resources/views/smarty/welcome.tpl *&rbrace;
&lbrace;extends file="layout.tpl"&rbrace;

&lbrace;block name="title"&rbrace;Welcome&lbrace;/block&rbrace;

&lbrace;block name="content"&rbrace;
    &lt;h1&gt;Hello &lbrace;$name&rbrace;!&lt;/h1&gt;

    &lbrace;if $user->isAdmin()&rbrace;
        &lt;p&gt;You have admin access.&lt;/p&gt;
    &lbrace;/if&rbrace;

    &lt;ul&gt;
    &lbrace;foreach $items as $item&rbrace;
        &lt;li&gt;&lbrace;$item.name&rbrace; - &lbrace;$item.price|number_format:2&rbrace;&lt;/li&gt;
    &lbrace;/foreach&rbrace;
    &lt;/ul&gt;
&lbrace;/block&rbrace;</code></pre>

    <h2>Usage in Controller</h2>
    <pre class="line-numbers"><code class="language-php">use Larafony\View\Smarty\SmartyRenderer;

final class HomeController extends Controller
{
    #[Route('/', methods: ['GET'])]
    public function index(SmartyRenderer $smarty): ResponseInterface
    {
        $html = $smarty->render('welcome.tpl', [
            'name' => 'John',
            'items' => Item::all(),
        ]);

        return new Response(200, [], $html);
    }
}</code></pre>

    <h2>Features</h2>
    <ul>
        <li><strong>Template inheritance</strong> - extends, block</li>
        <li><strong>Modifiers</strong> - date_format, number_format, escape</li>
        <li><strong>Control structures</strong> - if, foreach, include</li>
        <li><strong>Plugins</strong> - Custom functions and modifiers</li>
        <li><strong>Caching</strong> - Compiled template caching</li>
    </ul>
</x-bridges-layout>
