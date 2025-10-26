<x-layout :title="$title" :description="$description">
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
                            Eloquent-inspired ORM with relationships, query builder, migrations, and seeders.
                            Database operations made simple.
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
                            Start the development server
                        </h4>
                        <pre class="line-numbers"><code class="language-bash">php8.5 -S localhost:8000 -t public</code></pre>
                    </div>

                    <div class="alert alert-info border-0 mt-4" style="background: rgba(99, 102, 241, 0.15); color: #cbd5e1;">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Requirements:</strong> PHP ≥ 8.5, Composer, MySQL/PostgreSQL/SQLite
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
                        © 2025 Larafony Framework. Open source and free forever.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</x-layout>
