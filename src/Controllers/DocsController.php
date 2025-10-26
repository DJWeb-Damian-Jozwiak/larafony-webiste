<?php

declare(strict_types=1);

namespace App\Controllers;

use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DocsController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/docs', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.index', [
            'title' => 'Documentation - Larafony Framework',
            'description' => 'Complete documentation for Larafony Framework - Modern PHP 8.5 framework',
        ]);
    }

    #[Route('/docs/structure', 'GET')]
    public function structure(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.structure', [
            'title' => 'Project Structure - Larafony Documentation',
            'description' => 'Learn about Larafony project structure, directories, and organization',
        ]);
    }

    #[Route('/docs/models', 'GET')]
    public function models(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.models', [
            'title' => 'Models & Relationships - Larafony Documentation',
            'description' => 'Create ORM models with attribute-based relationships in Larafony',
        ]);
    }

    #[Route('/docs/controllers', 'GET')]
    public function controllers(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.controllers', [
            'title' => 'Controllers & Routing - Larafony Documentation',
            'description' => 'Define routes with PHP attributes and create controllers in Larafony',
        ]);
    }

    #[Route('/docs/validation', 'GET')]
    public function validation(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.validation', [
            'title' => 'DTO Validation - Larafony Documentation',
            'description' => 'Type-safe validation using PHP 8.5 attributes and property hooks',
        ]);
    }

    #[Route('/docs/middleware', 'GET')]
    public function middleware(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.middleware', [
            'title' => 'Middleware - Larafony Documentation',
            'description' => 'Create PSR-15 compliant middleware in Larafony Framework',
        ]);
    }

    #[Route('/docs/bootstrap', 'GET')]
    public function bootstrap(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.bootstrap', [
            'title' => 'Application Bootstrap - Larafony Documentation',
            'description' => 'Configure your Larafony application with service providers and routes',
        ]);
    }
}
