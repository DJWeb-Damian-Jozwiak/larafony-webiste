<x-docs-layout :title="$title" :description="$description">
    <h1 class="gradient-text">Welcome to Larafony Documentation</h1>
    <p class="lead text-white-50">
        Modern PHP 8.5 framework built for clarity, not complexity.
    </p>

    <div class="alert-docs alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        <strong>Getting Started:</strong> New to Larafony? Start with the <a href="/docs/structure">Project Structure</a> guide to understand how everything is organized.
    </div>

    <h2>What is Larafony?</h2>
    <p>
        Larafony is a production-ready PHP 8.5 framework that combines the developer experience of Laravel with the robustness of Symfony,
        all while staying true to PSR standards. It's designed for developers who want:
    </p>
    <ul>
        <li><strong>Modern PHP</strong> - Full PHP 8.5 support with attributes, property hooks, and asymmetric visibility</li>
        <li><strong>PSR Compliance</strong> - Built on PSR-7, PSR-11, PSR-15, and PSR-3 standards</li>
        <li><strong>Zero Magic</strong> - Clear, explicit code you can understand and modify</li>
        <li><strong>Attribute-Based</strong> - Routes, relationships, validation—all defined with native PHP attributes</li>
    </ul>

    <h2>Quick Start</h2>
    <p>Get up and running with Larafony in minutes:</p>

    <pre class="line-numbers"><code class="language-bash"># Create a new project
composer create-project larafony/skeleton my-app

# Navigate to your project
cd my-app

# Start the development server
php8.5 -S localhost:8000 -t public</code></pre>

    <p>Visit <code>http://localhost:8000</code> and you're ready to go!</p>

    <h2>Core Features</h2>

    <h3><i class="bi bi-database text-primary me-2"></i>Active Record ORM</h3>
    <p>
        Eloquent-inspired ORM with attribute-based relationships. Define your models once and access relationships
        through property hooks.
    </p>
    <p><a href="/docs/models">Read Models & Relationships Guide →</a></p>

    <h3><i class="bi bi-signpost-2 text-primary me-2"></i>Attribute Routing</h3>
    <p>
        Define routes directly on controller methods using PHP 8 attributes. No separate route files to maintain.
    </p>
    <p><a href="/docs/controllers">Read Controllers & Routing Guide →</a></p>

    <h3><i class="bi bi-shield-check text-primary me-2"></i>Type-Safe DTOs</h3>
    <p>
        Validate incoming requests with DTO classes that leverage PHP 8.5 property hooks and attributes for automatic validation.
    </p>
    <p><a href="/docs/validation">Read DTO Validation Guide →</a></p>

    <h3><i class="bi bi-filter-circle text-primary me-2"></i>PSR-15 Middleware</h3>
    <p>
        Create middleware following PSR-15 standards. Attach them to routes using attributes for clean, declarative code.
    </p>
    <p><a href="/docs/middleware">Read Middleware Guide →</a></p>

    <h2>Philosophy</h2>
    <blockquote style="border-left: 4px solid var(--primary-color); padding-left: 1.5rem; margin: 2rem 0; font-style: italic; color: #cbd5e1;">
        "The best framework is the one you can replace piece by piece - because you understand it completely."
    </blockquote>
    <p>
        Larafony is not just a framework—it's a learning tool. Every component is designed to be:
    </p>
    <ul>
        <li><strong>Understandable</strong> - Clear code without hidden magic</li>
        <li><strong>Replaceable</strong> - Swap any component for your preferred library</li>
        <li><strong>Educational</strong> - Learn how modern frameworks work under the hood</li>
    </ul>

    <h2>Getting Started</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-folder me-2 text-primary"></i>Project Structure</h4>
                <p class="text-white-50 mb-3">Learn how Larafony projects are organized and where to find everything.</p>
                <a href="/docs/structure" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-gear me-2 text-primary"></i>Configuration & .env</h4>
                <p class="text-white-50 mb-3">Manage application configuration and environment variables.</p>
                <a href="/docs/config" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-box me-2 text-primary"></i>Container (PSR-11)</h4>
                <p class="text-white-50 mb-3">Dependency injection container with automatic autowiring.</p>
                <a href="/docs/container" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-signpost-2 me-2 text-primary"></i>Controllers & Routing</h4>
                <p class="text-white-50 mb-3">Define routes with attributes and create RESTful controllers.</p>
                <a href="/docs/controllers" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Database</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-table me-2 text-primary"></i>Schema Builder & Migrations</h4>
                <p class="text-white-50 mb-3">Build database schemas with migrations using pipe operator.</p>
                <a href="/docs/schema-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-search me-2 text-primary"></i>Query Builder</h4>
                <p class="text-white-50 mb-3">Build and execute SQL queries with fluent, type-safe API.</p>
                <a href="/docs/query-builder" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-database me-2 text-primary"></i>Models & Relationships</h4>
                <p class="text-white-50 mb-3">Active Record ORM with attribute-based relationships.</p>
                <a href="/docs/models" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Views & Validation</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-eye me-2 text-primary"></i>Views & Blade</h4>
                <p class="text-white-50 mb-3">Build dynamic views with Blade templates and components.</p>
                <a href="/docs/views" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-shield-check me-2 text-primary"></i>DTO Validation</h4>
                <p class="text-white-50 mb-3">Validate requests with type-safe DTOs and property hooks.</p>
                <a href="/docs/validation" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <h2 class="mt-5">HTTP & Logging</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-filter-circle me-2 text-primary"></i>Middleware</h4>
                <p class="text-white-50 mb-3">Create PSR-15 compliant middleware for request/response processing.</p>
                <a href="/docs/middleware" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-arrow-left-right me-2 text-primary"></i>HTTP Client (PSR-18)</h4>
                <p class="text-white-50 mb-3">Make HTTP requests to external APIs with PSR-18 client.</p>
                <a href="/docs/http-client" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-journal-code me-2 text-primary"></i>Logging (PSR-3)</h4>
                <p class="text-white-50 mb-3">Track application events with PSR-3 compliant logging.</p>
                <a href="/docs/logging" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Background Processing</h2>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.5rem;">
                <h4><i class="bi bi-hourglass-split me-2 text-primary"></i>Queue & Jobs</h4>
                <p class="text-white-50 mb-3">Enterprise-grade job scheduling with ORM, Clock integration, and UUID support.</p>
                <a href="/docs/queue-jobs" class="btn btn-sm btn-outline-light">
                    Read Guide <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</x-docs-layout>
