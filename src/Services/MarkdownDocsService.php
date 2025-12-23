<?php

declare(strict_types=1);

namespace App\Services;

use Larafony\Docs\DocsProvider;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

final class MarkdownDocsService
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Get all sections with their pages
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSections(): array
    {
        return DocsProvider::getSections();
    }

    /**
     * Find a document by section and slug
     *
     * @return array{content: string, title: string, description: string, section: string, sectionId: string}|null
     */
    public function findDocument(string $section, string $slug): ?array
    {
        $sectionData = DocsProvider::findSection($section);

        if ($sectionData === null) {
            return null;
        }

        $page = DocsProvider::findPage($section, $slug);

        if ($page === null) {
            return null;
        }

        $path = $section . '/' . $page['file'];
        $parsed = DocsProvider::parse($path);

        if ($parsed === null) {
            return null;
        }

        $html = $this->converter->convert($parsed['content'])->getContent();
        $html = $this->enhanceCodeBlocks($html);

        return [
            'content' => $html,
            'title' => $parsed['frontmatter']['title'] ?? $page['title'],
            'description' => $parsed['frontmatter']['description'] ?? '',
            'section' => $sectionData['title'],
            'sectionId' => $section,
        ];
    }

    /**
     * Find document by URL path (e.g., /docs/controllers or /bridges/guzzle)
     *
     * @return array{content: string, title: string, description: string, section: string, sectionId: string}|null
     */
    public function findByPath(string $path): ?array
    {
        // Remove leading slash and prefix
        $path = ltrim($path, '/');

        // Handle /docs prefix
        if (str_starts_with($path, 'docs/')) {
            $path = substr($path, 5);
        } elseif ($path === 'docs') {
            $path = '';
        }

        // Handle /bridges prefix
        if (str_starts_with($path, 'bridges/')) {
            $slug = substr($path, 8);
            return $this->findDocument('bridges', $slug);
        } elseif ($path === 'bridges') {
            return $this->findDocument('bridges', 'overview');
        }

        // Map path to section and slug
        $pathMapping = $this->getPathMapping();

        if (isset($pathMapping[$path])) {
            [$section, $slug] = $pathMapping[$path];
            return $this->findDocument($section, $slug);
        }

        return null;
    }

    /**
     * Get mapping of URL paths to section/slug pairs
     *
     * @return array<string, array{0: string, 1: string}>
     */
    private function getPathMapping(): array
    {
        return [
            '' => ['getting-started', 'index'],
            'structure' => ['getting-started', 'structure'],
            'bootstrap' => ['getting-started', 'bootstrap'],
            'container' => ['architecture', 'container'],
            'config' => ['architecture', 'config'],
            'auth' => ['architecture', 'auth'],
            'cache' => ['architecture', 'cache'],
            'controllers' => ['http', 'controllers'],
            'middleware' => ['http', 'middleware'],
            'http-client' => ['http', 'http-client'],
            'schema-builder' => ['database', 'schema-builder'],
            'query-builder' => ['database', 'query-builder'],
            'models' => ['database', 'models'],
            'views' => ['views', 'views'],
            'inertia' => ['views', 'inertia'],
            'validation' => ['views', 'validation'],
            'encryption' => ['security', 'encryption'],
            'session-cookies' => ['security', 'session-cookies'],
            'mail' => ['communication', 'mail'],
            'logging' => ['utilities', 'logging'],
            'events' => ['utilities', 'events'],
            'queue-jobs' => ['async', 'queue-jobs'],
            'websockets' => ['async', 'websockets'],
            'debugbar' => ['debugging', 'debugbar'],
            'error-handling/web' => ['debugging', 'error-handling/web'],
            'error-handling/console' => ['debugging', 'error-handling/console'],
        ];
    }

    /**
     * Enhance code blocks with Prism.js classes
     */
    private function enhanceCodeBlocks(string $html): string
    {
        // Add line-numbers class to pre tags and language class
        $html = preg_replace_callback(
            '/<pre><code class="language-(\w+)">/s',
            fn($m) => '<pre class="line-numbers"><code class="language-' . $m[1] . '">',
            $html
        ) ?? $html;

        // Handle code blocks without language
        $html = preg_replace(
            '/<pre><code>/',
            '<pre class="line-numbers"><code class="language-text">',
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * Build sidebar navigation
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSidebar(string $baseUrl = '/docs'): array
    {
        $sections = $this->getSections();
        $sidebar = [];

        foreach ($sections as $section) {
            // Skip bridges section for docs sidebar
            if ($section['id'] === 'bridges' && $baseUrl === '/docs') {
                continue;
            }

            $sidebarSection = [
                'id' => $section['id'],
                'title' => $section['title'],
                'icon' => $section['icon'] ?? 'file-text',
                'pages' => [],
            ];

            foreach ($section['pages'] as $page) {
                $url = $baseUrl;
                if ($section['id'] === 'bridges') {
                    $url = '/bridges';
                }
                if (!empty($page['slug'])) {
                    $url .= '/' . $page['slug'];
                }

                $sidebarSection['pages'][] = [
                    'title' => $page['title'],
                    'url' => $url,
                ];
            }

            $sidebar[] = $sidebarSection;
        }

        return $sidebar;
    }
}
