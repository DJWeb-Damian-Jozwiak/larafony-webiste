<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title>{{ $title ?? 'Documentation - Larafony Framework' }}</title>
    <meta name="description" content="{{ $description ?? 'Complete documentation for Larafony Framework - Modern PHP 8.5 framework' }}">
    <meta name="keywords" content="Larafony, PHP 8.5, Framework Documentation, PSR, Modern PHP, Attribute-based, ORM, Blade Templates, Middleware, Type-safe, Tutorial">
    <meta name="author" content="Larafony">
    <link rel="canonical" href="https://larafony.com{{ $_SERVER['REQUEST_URI'] ?? '/docs' }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://larafony.com/docs">
    <meta property="og:title" content="{{ $title ?? 'Documentation - Larafony Framework' }}">
    <meta property="og:description" content="{{ $description ?? 'Complete documentation for Larafony Framework' }}">
    <meta property="og:image" content="/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://larafony.com/docs">
    <meta property="twitter:title" content="{{ $title ?? 'Documentation - Larafony Framework' }}">
    <meta property="twitter:description" content="{{ $description ?? 'Complete documentation for Larafony Framework' }}">
    <meta property="twitter:image" content="/logo.png">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/logo.png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">

    <!-- Prism.js CSS - Tomorrow Night Theme -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #ec4899;
            --accent-color: #8b5cf6;
            --dark-bg: #0f172a;
            --darker-bg: #020617;
            --text-muted: #94a3b8;
            --border-color: #1e293b;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.7;
            color: #e2e8f0;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
            min-height: 100vh;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Header */
        .docs-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .docs-logo {
            max-width: 40px;
            height: auto;
        }

        /* Sidebar */
        .docs-sidebar {
            position: fixed;
            top: 73px;
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - 73px);
            background: rgba(15, 23, 42, 0.6);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            padding: 2rem 0;
        }

        .docs-sidebar nav {
            padding: 0 1.5rem;
        }

        .docs-sidebar .nav-section {
            margin-bottom: 2rem;
        }

        .docs-sidebar .nav-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .docs-sidebar .nav-section-title:hover {
            color: var(--primary-color);
        }

        .docs-sidebar .collapse-icon {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .docs-sidebar .nav-section-title:not(.collapsed) .collapse-icon {
            transform: rotate(90deg);
        }

        .docs-sidebar .nav-link {
            display: block;
            padding: 0.5rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
        }

        .docs-sidebar .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .docs-sidebar .nav-link.active {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .docs-content {
            margin-left: var(--sidebar-width);
            padding: 3rem 2rem;
            max-width: 1000px;
        }

        .docs-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .docs-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-top: 3rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .docs-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .docs-content p {
            margin-bottom: 1.5rem;
            color: #cbd5e1;
        }

        .docs-content ul, .docs-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
            color: #cbd5e1;
        }

        .docs-content li {
            margin-bottom: 0.5rem;
        }

        .docs-content code:not([class*="language-"]) {
            background: rgba(251, 146, 60, 0.15);
            color: #fb923c;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.9em;
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        /* Prism.js overrides */
        pre[class*="language-"] {
            margin: 1.5rem 0 2rem 0;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            background: #1e1e1e !important;
        }

        code[class*="language-"] {
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .line-numbers .line-numbers-rows {
            border-right: 1px solid var(--border-color);
        }

        /* Alert boxes */
        .alert-docs {
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin: 1.5rem 0;
            border-left: 4px solid;
        }

        .alert-docs.alert-info {
            background: rgba(99, 102, 241, 0.15);
            border-color: var(--primary-color);
            color: #cbd5e1;
        }

        .alert-docs.alert-success {
            background: rgba(34, 197, 94, 0.15);
            border-color: #22c55e;
            color: #cbd5e1;
        }

        .alert-docs.alert-warning {
            background: rgba(251, 191, 36, 0.15);
            border-color: #fbbf24;
            color: #cbd5e1;
        }

        .alert-docs.alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border-color: #ef4444;
            color: #cbd5e1;
        }

        /* Footer */
        .docs-footer {
            margin-left: var(--sidebar-width);
            margin-top: 4rem;
            padding: 2rem;
            border-top: 1px solid var(--border-color);
            text-align: center;
            color: var(--text-muted);
        }

        .docs-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .docs-footer a:hover {
            text-decoration: underline;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .docs-sidebar {
                display: none;
            }

            .docs-content,
            .docs-footer {
                margin-left: 0;
            }
        }

        /* Scrollbar */
        .docs-sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .docs-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .docs-sidebar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        .docs-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="docs-header">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <a href="/" class="d-flex align-items-center text-decoration-none">
                    <img src="/logo.png" alt="Larafony" class="docs-logo me-2">
                    <span class="text-white fw-bold">Larafony</span>
                    <span class="text-muted ms-2">/ Documentation</span>
                </a>
                <div>
                    <a href="/" class="btn btn-sm btn-outline-light me-2">
                        <i class="bi bi-house"></i> Home
                    </a>
                    <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony" target="_blank" class="btn btn-sm btn-outline-light me-2">
                        <i class="bi bi-github"></i> Framework
                    </a>
                    <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-code-square"></i> Demo
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="docs-sidebar">
        <nav>
            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-getting-started" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Getting Started
                </div>
                <div id="nav-getting-started" class="collapse">
                    <a href="/docs" class="nav-link">
                        <i class="bi bi-book me-2"></i>Introduction
                    </a>
                    <a href="/docs/structure" class="nav-link">
                        <i class="bi bi-folder me-2"></i>Project Structure
                    </a>
                    <a href="/docs/bootstrap" class="nav-link">
                        <i class="bi bi-gear me-2"></i>Application Bootstrap
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-architecture" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Architecture
                </div>
                <div id="nav-architecture" class="collapse">
                    <a href="/docs/container" class="nav-link">
                        <i class="bi bi-box me-2"></i>Container (PSR-11)
                    </a>
                    <a href="/docs/config" class="nav-link">
                        <i class="bi bi-gear-fill me-2"></i>Configuration & .env
                    </a>
                    <a href="/docs/auth" class="nav-link">
                        <i class="bi bi-shield-lock-fill me-2"></i>Auth - Roles & Permissions
                    </a>
                    <a href="/docs/cache" class="nav-link">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Cache Optimization
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-http" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>HTTP Layer
                </div>
                <div id="nav-http" class="collapse">
                    <a href="/docs/controllers" class="nav-link">
                        <i class="bi bi-signpost-2 me-2"></i>Controllers & Routing
                    </a>
                    <a href="/docs/middleware" class="nav-link">
                        <i class="bi bi-filter-circle me-2"></i>Middleware
                    </a>
                    <a href="/docs/http-client" class="nav-link">
                        <i class="bi bi-arrow-left-right me-2"></i>HTTP Client (PSR-18)
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-database" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Database
                </div>
                <div id="nav-database" class="collapse">
                    <a href="/docs/schema-builder" class="nav-link">
                        <i class="bi bi-table me-2"></i>Schema Builder
                    </a>
                    <a href="/docs/query-builder" class="nav-link">
                        <i class="bi bi-search me-2"></i>Query Builder
                    </a>
                    <a href="/docs/models" class="nav-link">
                        <i class="bi bi-database me-2"></i>Models & ORM
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-views" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Views & Validation
                </div>
                <div id="nav-views" class="collapse">
                    <a href="/docs/views" class="nav-link">
                        <i class="bi bi-eye me-2"></i>Views & Blade
                    </a>
                    <a href="/docs/inertia" class="nav-link">
                        <i class="bi bi-lightning-charge me-2"></i>Inertia.js & Vue
                    </a>
                    <a href="/docs/validation" class="nav-link">
                        <i class="bi bi-shield-check me-2"></i>DTO Validation
                    </a>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-error-handling" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Error Handling
                </div>
                <div id="nav-error-handling" class="collapse">
                    <a href="/docs/error-handling/web" class="nav-link">
                        <i class="bi bi-globe me-2"></i>Web Error Pages
                    </a>
                    <a href="/docs/error-handling/console" class="nav-link">
                        <i class="bi bi-terminal me-2"></i>Console Debugging 🎯
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-security" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Security
                </div>
                <div id="nav-security" class="collapse">
                    <a href="/docs/encryption" class="nav-link">
                        <i class="bi bi-shield-lock me-2"></i>Encryption
                    </a>
                    <a href="/docs/session-cookies" class="nav-link">
                        <i class="bi bi-cookie me-2"></i>Sessions & Cookies
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-communication" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Communication
                </div>
                <div id="nav-communication" class="collapse">
                    <a href="/docs/mail" class="nav-link">
                        <i class="bi bi-envelope me-2"></i>Sending Emails
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-utilities" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Utilities
                </div>
                <div id="nav-utilities" class="collapse">
                    <a href="/docs/logging" class="nav-link">
                        <i class="bi bi-journal-code me-2"></i>Logging (PSR-3)
                    </a>
                    <a href="/docs/events" class="nav-link">
                        <i class="bi bi-broadcast me-2"></i>Event System (PSR-14)
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title" data-bs-toggle="collapse" data-bs-target="#nav-debugging" style="cursor: pointer;">
                    <i class="bi bi-chevron-right me-1 collapse-icon"></i>Debugging
                </div>
                <div id="nav-debugging" class="collapse">
                    <a href="/docs/debugbar" class="nav-link">
                        <i class="bi bi-bug me-2"></i>DebugBar & Eager Loading
                    </a>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Resources</div>
                <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony" target="_blank" class="nav-link">
                    <i class="bi bi-github me-2"></i>Framework Repository
                </a>
                <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="nav-link">
                    <i class="bi bi-code-square me-2"></i>Demo App Repository
                </a>
                <a href="https://masterphp.eu" target="_blank" class="nav-link">
                    <i class="bi bi-mortarboard me-2"></i>Learn How It's Built
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="docs-content">
        {!! $slot !!}
    </main>

    <!-- Footer -->
    <footer class="docs-footer">
        <p class="mb-2">
            Want to learn how Larafony is built from scratch?
        </p>
        <p>
            <a href="https://masterphp.eu" target="_blank">
                <i class="bi bi-mortarboard me-1"></i>
                Learn How It's Built at MasterPHP.eu
            </a>
        </p>
        <p class="mt-3 small">
            © 2025 Larafony Framework. Licensed under <a href="https://opensource.org/licenses/MIT" target="_blank" style="color: var(--primary-color);">MIT License</a>.
        </p>
        <p class="small">
            <a href="/privacy" style="color: var(--text-muted); text-decoration: none;">Privacy Policy</a>
        </p>
        <p>
            Built with <i class="bi bi-heart-fill text-danger"></i> using PHP 8.5 & Larafony
        </p>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Prism.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>

    <!-- Analytics Tracker (Beacon API) -->
    <script src="/js/analytics.js"></script>

    <!-- Active nav link and auto-expand section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;

            // Find and mark active link
            document.querySelectorAll('.docs-sidebar .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');

                    // Find parent collapse div and expand it
                    const collapseParent = link.closest('.collapse');
                    if (collapseParent) {
                        collapseParent.classList.add('show');

                        // Find the title that controls this collapse and remove collapsed class
                        const titleElement = document.querySelector(`[data-bs-target="#${collapseParent.id}"]`);
                        if (titleElement) {
                            titleElement.classList.remove('collapsed');
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
