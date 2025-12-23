<?php

declare(strict_types=1);

namespace App\Controllers;

use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BridgesController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/bridges', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.index', [
            'title' => 'Bridge Packages - Larafony Framework',
            'description' => 'Official bridge packages to integrate popular PHP libraries with Larafony Framework',
        ]);
    }

    #[Route('/bridges/guzzle', 'GET')]
    public function guzzle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.guzzle', [
            'title' => 'Guzzle HTTP Bridge - Larafony Framework',
            'description' => 'Integrate Guzzle HTTP client with Larafony for async requests, middleware, and PSR-18 compatibility',
        ]);
    }

    #[Route('/bridges/monolog', 'GET')]
    public function monolog(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.monolog', [
            'title' => 'Monolog Logging Bridge - Larafony Framework',
            'description' => 'Professional logging with Monolog - multiple channels, handlers, and PSR-3 compatibility',
        ]);
    }

    #[Route('/bridges/symfony-mailer', 'GET')]
    public function symfonyMailer(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.symfony-mailer', [
            'title' => 'Symfony Mailer Bridge - Larafony Framework',
            'description' => 'Send emails with Symfony Mailer - SMTP, SES, Mailgun, SendGrid and more',
        ]);
    }

    #[Route('/bridges/flysystem', 'GET')]
    public function flysystem(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.flysystem', [
            'title' => 'Flysystem Storage Bridge - Larafony Framework',
            'description' => 'Unified filesystem abstraction with Flysystem - local, S3, FTP, and more',
        ]);
    }

    #[Route('/bridges/carbon', 'GET')]
    public function carbon(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.carbon', [
            'title' => 'Carbon Clock Bridge - Larafony Framework',
            'description' => 'PSR-20 Clock implementation using Carbon for powerful date/time handling',
        ]);
    }

    #[Route('/bridges/phpdotenv', 'GET')]
    public function phpdotenv(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.phpdotenv', [
            'title' => 'PHP dotenv Bridge - Larafony Framework',
            'description' => 'Load environment variables from .env files using vlucas/phpdotenv',
        ]);
    }

    #[Route('/bridges/twig', 'GET')]
    public function twig(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.twig', [
            'title' => 'Twig Template Bridge - Larafony Framework',
            'description' => 'Use Twig templating engine as an alternative to Blade',
        ]);
    }

    #[Route('/bridges/smarty', 'GET')]
    public function smarty(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.smarty', [
            'title' => 'Smarty Template Bridge - Larafony Framework',
            'description' => 'Use Smarty templating engine as an alternative to Blade',
        ]);
    }

    #[Route('/bridges/debugbar', 'GET')]
    public function debugbarBridge(ServerRequestInterface $request): ResponseInterface
    {
        return $this->render('bridges.debugbar', [
            'title' => 'PHP DebugBar Bridge - Larafony Framework',
            'description' => 'Integrate PHP DebugBar for visual debugging in browser',
        ]);
    }
}
