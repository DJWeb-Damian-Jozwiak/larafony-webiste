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

    #[Route('/docs/container', 'GET')]
    public function container(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.container', [
            'title' => 'Container (PSR-11) - Larafony Documentation',
            'description' => 'Dependency injection container with automatic autowiring',
        ]);
    }

    #[Route('/docs/config', 'GET')]
    public function config(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.config', [
            'title' => 'Configuration & Environment - Larafony Documentation',
            'description' => 'Manage configuration files and environment variables',
        ]);
    }

    #[Route('/docs/schema-builder', 'GET')]
    public function schemaBuilder(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.schema-builder', [
            'title' => 'Schema Builder & Migrations - Larafony Documentation',
            'description' => 'Build and modify database schemas with migrations and pipe operator',
        ]);
    }

    #[Route('/docs/query-builder', 'GET')]
    public function queryBuilder(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.query-builder', [
            'title' => 'Query Builder - Larafony Documentation',
            'description' => 'Build and execute SQL queries with fluent, type-safe API',
        ]);
    }

    #[Route('/docs/views', 'GET')]
    public function views(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.views', [
            'title' => 'Views & Blade - Larafony Documentation',
            'description' => 'Build dynamic views with Blade templates and components',
        ]);
    }

    #[Route('/docs/http-client', 'GET')]
    public function httpClient(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.http-client', [
            'title' => 'HTTP Client (PSR-18) - Larafony Documentation',
            'description' => 'Make HTTP requests to external APIs with PSR-18 client',
        ]);
    }

    #[Route('/docs/logging', 'GET')]
    public function logging(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.logging', [
            'title' => 'Logging (PSR-3) - Larafony Documentation',
            'description' => 'Track application events with PSR-3 compliant logging',
        ]);
    }

    #[Route('/docs/inertia', 'GET')]
    public function inertiaDocs(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.inertia', [
            'title' => 'Inertia.js & Vue - Larafony Documentation',
            'description' => 'Build modern SPAs with Inertia.js and Vue.js using server-side routing',
        ]);
    }

    #[Route('/docs/error-handling/web', 'GET')]
    public function errorHandlingWeb(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.error-handling-web', [
            'title' => 'Web Error Handling - Larafony Framework',
            'description' => 'Comprehensive error handling system with beautiful debug views and production error pages',
            'activeMenu' => 'error-handling-web',
        ]);
    }

    #[Route('/docs/error-handling/console', 'GET')]
    public function errorHandlingConsole(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('docs.console-debugging', [
            'title' => 'Console Interactive Debugging - Larafony Framework',
            'description' => 'The only PHP framework with native, built-from-scratch interactive error debugging in the console - REPL-like experience for CLI commands',
            'activeMenu' => 'error-handling-console',
        ]);
    }
}
