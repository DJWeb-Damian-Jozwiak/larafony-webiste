<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\MarkdownDocsService;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller for rendering documentation from Markdown files.
 *
 * Uses larafony/docs package as single source of truth.
 */
class DocsController extends Controller
{
    private MarkdownDocsService $docsService;

    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
        $this->docsService = new MarkdownDocsService();
    }

    #[Route('/docs', 'GET', name: 'docs.index')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderDoc('');
    }

    #[Route('/docs/error-handling/<type:[a-z]+>', 'GET', name: 'docs.error-handling')]
    public function errorHandling(ServerRequestInterface $request, string $type): ResponseInterface
    {
        return $this->renderDoc('error-handling/' . $type);
    }

    #[Route('/docs/<slug:[a-z\-]+>', 'GET', name: 'docs.show')]
    public function show(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        return $this->renderDoc($slug);
    }

    #[Route('/bridges', 'GET', name: 'bridges.index')]
    public function bridgesIndex(ServerRequestInterface $request): ResponseInterface
    {
        return $this->renderBridge('');
    }

    #[Route('/bridges/<slug:[a-z\-]+>', 'GET', name: 'bridges.show')]
    public function bridgesShow(ServerRequestInterface $request, string $slug): ResponseInterface
    {
        return $this->renderBridge($slug);
    }

    private function renderDoc(string $path): ResponseInterface
    {
        $doc = $this->docsService->findByPath('docs/' . $path);

        if ($doc === null) {
            return $this->render('errors.404', [
                'title' => '404 - Page Not Found',
                'description' => 'The requested documentation page was not found.',
            ]);
        }

        return $this->render('markdown-docs', [
            'title' => $doc['title'] . ' - Larafony Documentation',
            'description' => $doc['description'],
            'content' => $doc['content'],
            'section' => $doc['section'],
            'sidebar' => $this->docsService->getSidebar('/docs'),
            'currentPath' => '/docs' . ($path ? '/' . $path : ''),
            'layout' => 'docs',
        ]);
    }

    private function renderBridge(string $slug): ResponseInterface
    {
        // Default to overview for empty slug
        if ($slug === '') {
            $slug = 'overview';
        }

        $doc = $this->docsService->findDocument('bridges', $slug);

        if ($doc === null) {
            return $this->render('errors.404', [
                'title' => '404 - Page Not Found',
                'description' => 'The requested bridge documentation was not found.',
            ]);
        }

        return $this->render('markdown-docs', [
            'title' => $doc['title'] . ' - Larafony Bridges',
            'description' => $doc['description'],
            'content' => $doc['content'],
            'section' => $doc['section'],
            'sidebar' => $this->docsService->getSidebar('/bridges'),
            'currentPath' => '/bridges/' . $slug,
            'layout' => 'bridges',
        ]);
    }
}
