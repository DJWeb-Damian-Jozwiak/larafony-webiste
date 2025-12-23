<x-layout :title="$title" :description="$description">
    <!-- Floating Search Button -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <button type="button" class="search-trigger" onclick="openDocsSearch()">
            <i class="bi bi-search"></i>
            <span class="d-none d-sm-inline">Search Docs</span>
            <kbd>Ctrl+F</kbd>
        </button>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <img src="/logo.png" alt="Larafony Framework Logo" class="logo-img">
                    <h1 class="display-3 fw-bold mb-4">
                        <span class="gradient-text">Larafony Framework</span>
                    </h1>
                    <p class="lead mb-5 text-white-50" style="font-size: 1.5rem;">
                        Modern PHP 8.5 framework — built for <strong>clarity</strong>, not complexity.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="#getting-started" class="btn btn-gradient btn-lg">
                            <i class="bi bi-rocket-takeoff me-2"></i>Get Started
                        </a>
                        <a href="/docs" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-book me-2"></i>Documentation
                        </a>
                        <a href="/bridges" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-plug me-2"></i>Bridges
                        </a>
                        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-github me-2"></i>Framework
                        </a>
                        <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-github me-2"></i>Demo App
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title gradient-text">Key Features</h2>
                <p class="section-subtitle">Everything you need to build modern PHP applications</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">PSR-Compliant</h3>
                        <p class="text-white-50">
                            Built on PSR-7 (HTTP), PSR-11 (Container), PSR-15 (Middleware), and PSR-3 (Logger).
                            Full interoperability with any PSR-compliant library.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="h4 mb-3">Type-Safe DTOs</h3>
                        <p class="text-white-50">
                            Leverage PHP 8.5 property hooks and attributes for automatic validation.
                            Type-safe data transfer with asymmetric visibility.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-palette-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Blade Templates</h3>
                        <p class="text-white-50">
                            Powerful, elegant templating engine with components, slots, and custom directives.
                            Write clean, maintainable views.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-signpost-2-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Attribute Routing</h3>
                        <p class="text-white-50">
                            Define routes with PHP attributes directly on controllers.
                            No configuration files, just clean, self-documenting code.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-database-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Active Record ORM</h3>
                        <p class="text-white-50">
                            Thanks to PHP 8.4+ property hooks, it was finally possible to create ORM in PHP behaving like Entity Framework from C#.
                            Relationships, query builder, migrations, and seeders included.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-funnel-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">PHP 8.5 Pipes</h3>
                        <p class="text-white-50">
                            Clean data transformation pipelines using the pipe operator.
                            Transform data with readable, functional code.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h3 class="h4 mb-3">Queue & Jobs</h3>
                        <p class="text-white-50">
                            ORM-based job queue with UUID support, Clock integration, and cron scheduling.
                            Process background tasks with database or Redis backends.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <h3 class="h4 mb-3">Event System (PSR-14)</h3>
                        <p class="text-white-50">
                            Event dispatcher with listener priority, stoppable propagation, and full PSR-14 compliance.
                            Decouple your application logic with events.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Cache (PSR-6)</h3>
                        <p class="text-white-50">
                            Multi-backend caching with Redis, Memcached, and file storage.
                            Authorization-aware cache for role-based content.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Authorization (RBAC)</h3>
                        <p class="text-white-50">
                            Built-in role and permission system with policy classes.
                            Fine-grained access control without external packages.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Encryption</h3>
                        <p class="text-white-50">
                            Modern libsodium XChaCha20-Poly1305 AEAD encryption.
                            Secure sessions, cookies, and sensitive data by default.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-cookie"></i>
                        </div>
                        <h3 class="h4 mb-3">Sessions & Cookies</h3>
                        <p class="text-white-50">
                            Encrypted session management with file and database drivers.
                            Secure, tamper-proof cookies out of the box.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Mail</h3>
                        <p class="text-white-50">
                            Native SMTP implementation with Mailable classes.
                            Send emails without external dependencies.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h3 class="h4 mb-3">Debug Toolbar</h3>
                        <p class="text-white-50">
                            Professional debug bar with query monitoring, N+1 detection, and performance metrics.
                            Automatic eager loading optimization.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bug-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Error Handling</h3>
                        <p class="text-white-50">
                            Beautiful debug views for web and interactive REPL-like debugging in console.
                            The only PHP framework with built-from-scratch CLI debugging.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-terminal-fill"></i>
                        </div>
                        <h3 class="h4 mb-3">Console Commands</h3>
                        <p class="text-white-50">
                            Artisan-like CLI with migrations, seeders, queue workers, and interactive debugging in terminal.
                            Beautiful console output and progress bars.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h3 class="h4 mb-3">HTTP Client (PSR-18)</h3>
                        <p class="text-white-50">
                            Make HTTP requests to external APIs with PSR-18 compliant client.
                            Clean, fluent interface for API integrations.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-window-stack"></i>
                        </div>
                        <h3 class="h4 mb-3">Inertia.js Support</h3>
                        <p class="text-white-50">
                            Build modern SPAs with Vue.js using server-side routing.
                            No separate API needed, full framework integration.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 class="h4 mb-3">Clock System (PSR-20)</h3>
                        <p class="text-white-50">
                            PSR-20 compliant clock for testable time operations.
                            Freeze time in tests, no more DateTimeImmutable headaches.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-journal-code"></i>
                        </div>
                        <h3 class="h4 mb-3">Logging (PSR-3)</h3>
                        <p class="text-white-50">
                            PSR-3 compliant logging with multiple channels and handlers.
                            Track application events with ease.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Getting Started Section -->
    <section id="getting-started" class="py-5 bg-dark bg-opacity-25">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title gradient-text">Getting Started</h2>
                <p class="section-subtitle">Up and running in seconds</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-1-circle-fill text-primary me-2"></i>
                            Create a new project
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">composer create-project larafony/skeleton my-app</code></pre>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-2-circle-fill text-primary me-2"></i>
                            Navigate to your project
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">cd my-app</code></pre>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-3-circle-fill text-primary me-2"></i>
                            Install frontend dependencies (Inertia.js + Vue)
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">npm install</code></pre>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-4-circle-fill text-primary me-2"></i>
                            Build frontend assets
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">npm run build</code></pre>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-5-circle-fill text-primary me-2"></i>
                            Run the installer (database setup + demo data)
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">php bin/larafony build:notes</code></pre>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-white mb-3">
                            <i class="bi bi-6-circle-fill text-primary me-2"></i>
                            Start the development server
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">php8.5 -S localhost:8000 -t public</code></pre>
                    </div>

                    <div class="alert alert-success border-0 mt-4" style="background: rgba(34, 197, 94, 0.15); color: #cbd5e1;">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i>
                        <strong>Done!</strong> Visit <a href="http://localhost:8000/notes" target="_blank" class="text-white fw-bold">localhost:8000/notes</a> to see the demo app with Blade templates and <a href="http://localhost:8000/inertia/notes" target="_blank" class="text-white fw-bold">localhost:8000/inertia/notes</a> for the Inertia.js version.
                    </div>

                    <div class="alert alert-warning border-0 mt-3" style="background: rgba(251, 191, 36, 0.15); color: #cbd5e1;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Optional - Redis/Memcached Cache:</strong><br>
                        As of Nov. 22, 2025, there is no <code class="text-warning">php8.5-memcached</code> packages available.<br><br>
                        <strong>Temporary solution:</strong> Run <code class="text-warning">./build.sh</code> from project root to compile extensions from source.<br><br>
                        <strong>For Redis:</strong> Simply run:
                        <ul class="mb-0 mt-2">
                            <li><strong>Debian/Ubuntu:</strong> <code class="text-warning">sudo apt install php8.5-redis</code></li>
                            <li><strong>RHEL/Fedora:</strong> <code class="text-warning">sudo dnf install php-redis</code> (via Remi repository)</li>
                            <li><strong>Windows:</strong> Use WSL2 with Ubuntu 😎 (or hope XAMPP/WAMPP adds latest PHP with cache drivers)</li>
                        </ul>
                        FileStorage cache works out of the box, no setup required.
                    </div>

                    <div class="alert alert-info border-0 mt-3" style="background: rgba(99, 102, 241, 0.15); color: #cbd5e1;">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Requirements:</strong> PHP ≥ 8.5, Composer, Node.js ≥ 22, MySQL/PostgreSQL/SQLite
                    </div>

                    <div class="alert alert-info border-0 mt-3" style="background: rgba(59, 130, 246, 0.15); color: #cbd5e1;">
                        <i class="bi bi-box-seam me-2"></i>
                        <strong>Composer v2.9 Required:</strong> Larafony & PHP 8.5 require Composer v2.9+. If you get any deprecation warnings, simply run <code class="text-info">composer self-update</code>.<br>
                        <small class="text-white-50 mt-2 d-block">
                            <strong>PS:</strong> Composer 2.9 can automatically not install vulnerable packages and automatically resolve conflicts in lock file, so it's highly recommended to update your Composer instance.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Philosophy Section -->
    <section id="philosophy" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title gradient-text">Philosophy</h2>
                <p class="section-subtitle">The framework you can truly understand</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="philosophy-quote mb-5">
                        "The best framework is the one you can replace piece by piece —
                        because you understand it completely."
                    </div>

                    <div class="row g-4 mt-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="text-white">Production-Ready from Day One</h5>
                                    <p class="text-white-50">
                                        Not a toy or tutorial framework. Built for real-world applications with
                                        high quality standards and full test coverage.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="text-white">Framework-Agnostic Mindset</h5>
                                    <p class="text-white-50">
                                        Use Blade, Twig, or Inertia.js. Swap components freely.
                                        No vendor lock-in, just pure PHP flexibility.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="text-white">Performance-First Architecture</h5>
                                    <p class="text-white-50">
                                        Minimal dependencies, optimized pipelines, and efficient patterns.
                                        Fast by design, not by accident.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div>
                                    <h5 class="text-white">Readable, Modern PHP Code</h5>
                                    <p class="text-white-50">
                                        Clean architecture, SOLID principles, and PHP 8.5 features.
                                        Code you'll be proud to maintain.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="https://masterphp.eu" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-book me-2"></i>Learn How It's Built
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-white-50">
                        Built with <i class="bi bi-heart-fill text-danger"></i> using Larafony Framework + PHP 8.5
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony" target="_blank" class="text-white-50 text-decoration-none me-3" title="Framework Repository">
                        <i class="bi bi-github" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app" target="_blank" class="text-white-50 text-decoration-none me-3" title="Demo App Repository">
                        <i class="bi bi-code-square" style="font-size: 1.5rem;"></i>
                    </a>
                    <a href="https://masterphp.eu" target="_blank" class="text-white-50 text-decoration-none">
                        <i class="bi bi-book" style="font-size: 1.5rem;"></i>
                    </a>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0 text-white-50 small">
                        © 2025 Larafony Framework. Licensed under <a href="https://opensource.org/licenses/MIT" target="_blank" class="text-white-50">MIT License</a>.
                    </p>
                    <p class="mb-0 text-white-50 small mt-2">
                        <a href="/privacy" class="text-white-50 text-decoration-none">Privacy Policy</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</x-layout>
