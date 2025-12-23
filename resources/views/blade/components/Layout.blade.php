<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    <title>{{ $title ?? 'Larafony Framework - Modern PHP 8.5 Framework' }}</title>
    <meta name="description" content="{{ $description ?? 'Larafony is a modern PHP 8.5 framework built for clarity, not complexity. PSR-compliant, attribute-based, and production-ready.' }}">
    <meta name="keywords" content="Larafony, PHP 8.5, Framework, PSR, Modern PHP, Attribute-based, ORM, Blade Templates, Middleware, Type-safe">
    <meta name="author" content="Larafony">
    <link rel="canonical" href="https://larafony.com{{ $_SERVER['REQUEST_URI'] ?? '/' }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://larafony.com/">
    <meta property="og:title" content="{{ $title ?? 'Larafony Framework - Modern PHP 8.5 Framework' }}">
    <meta property="og:description" content="{{ $description ?? 'Larafony is a modern PHP 8.5 framework built for clarity, not complexity.' }}">
    <meta property="og:image" content="/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://larafony.com/">
    <meta property="twitter:title" content="{{ $title ?? 'Larafony Framework - Modern PHP 8.5 Framework' }}">
    <meta property="twitter:description" content="{{ $description ?? 'Larafony is a modern PHP 8.5 framework built for clarity, not complexity.' }}">
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #f8fafc;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
            min-height: 100vh;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
            color: white;
        }

        .btn-gradient-alt {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-gradient-alt:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
            color: white;
        }

        .feature-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .feature-icon {
            width: 3.5rem;
            height: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
        }

        /* Prism.js overrides for landing page */
        pre[class*="language-"] {
            margin: 1.5rem 0;
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

        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
        }

        .hero-section {
            padding: 6rem 0 4rem;
            text-align: center;
        }

        .logo-img {
            max-width: 180px;
            height: auto;
            margin-bottom: 2rem;
            filter: drop-shadow(0 10px 30px rgba(99, 102, 241, 0.5));
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            background: var(--darker-bg);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0;
            margin-top: 6rem;
        }

        .philosophy-quote {
            background: rgba(99, 102, 241, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 2rem;
            border-radius: 0.5rem;
            font-size: 1.25rem;
            font-style: italic;
            color: #cbd5e1;
        }

        section {
            padding: 4rem 0;
        }

        .navbar-custom {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
        }

        /* Search trigger button */
        .search-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .search-trigger:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: var(--primary-color);
            color: #e2e8f0;
        }

        .search-trigger kbd {
            background: var(--border-color);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            border: 1px solid #475569;
            font-family: inherit;
        }

        @media (max-width: 768px) {
            .search-trigger kbd {
                display: none;
            }
        }

        /* MCP Modal Styling */
        .mcp-modal-content {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 1.5rem;
            overflow: hidden;
            position: relative;
            box-shadow: 0 25px 80px rgba(99, 102, 241, 0.4), 0 0 60px rgba(139, 92, 246, 0.2);
        }

        .mcp-modal-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 70%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
            animation: glowRotate 10s linear infinite;
            pointer-events: none;
        }

        @keyframes glowRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .mcp-icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.5);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 10px 40px rgba(99, 102, 241, 0.5); }
            50% { box-shadow: 0 10px 60px rgba(99, 102, 241, 0.8), 0 0 30px rgba(236, 72, 153, 0.4); }
        }

        .mcp-server-url {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .mcp-url {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            color: #10b981;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .copy-btn {
            padding: 0.35rem 0.6rem;
            border-radius: 0.4rem;
        }

        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* MCP Pills Tabs */
        .mcp-tabs {
            gap: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
            border-radius: 1rem;
        }

        .mcp-tabs .nav-link {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .mcp-tabs .nav-link i {
            font-size: 1.25rem;
        }

        .mcp-tabs .nav-link span {
            font-size: 0.75rem;
        }

        .mcp-tabs .nav-link:hover {
            color: #e2e8f0;
            background: rgba(99, 102, 241, 0.15);
        }

        .mcp-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .mcp-tab-content {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 1rem;
            padding: 1.5rem;
            min-height: 180px;
        }

        .mcp-tab-content pre {
            margin: 0.75rem 0 0;
            border-radius: 0.75rem;
            background: #1a1a2e !important;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .mcp-tab-content code {
            font-size: 0.85rem;
        }

        /* Feature Pills */
        .mcp-features {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .mcp-feature-pill {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #cbd5e1;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .mcp-feature-pill:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .mcp-feature-pill i {
            color: var(--primary-color);
        }

        /* Modal backdrop */
        #mcpModal .modal-backdrop {
            backdrop-filter: blur(8px);
        }

        @media (max-width: 768px) {
            .mcp-tabs .nav-link {
                padding: 0.5rem 0.75rem;
            }
            .mcp-tabs .nav-link span {
                display: none;
            }
            .mcp-tabs .nav-link i {
                font-size: 1.5rem;
            }
            .mcp-server-url {
                text-align: center;
            }
            .mcp-server-url .d-flex {
                flex-direction: column;
            }
            .mcp-icon-wrapper {
                width: 60px;
                height: 60px;
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    {!! $slot !!}

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Prism.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>

    <!-- MiniSearch for documentation search -->
    <script src="https://cdn.jsdelivr.net/npm/minisearch@7.1.1/dist/umd/index.min.js"></script>
    <script src="/js/docs-search.js"></script>

    <!-- Smooth scroll animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to elements as they come into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });

            // Re-highlight code when MCP tabs are switched
            const mcpTabs = document.getElementById('mcpTabs');
            if (mcpTabs) {
                mcpTabs.addEventListener('shown.bs.tab', function(e) {
                    Prism.highlightAll();
                });
            }

            // Re-highlight code when modal is shown
            const mcpModal = document.getElementById('mcpModal');
            if (mcpModal) {
                mcpModal.addEventListener('shown.bs.modal', function(e) {
                    Prism.highlightAll();
                });
            }
        });

        // Copy MCP URL function
        function copyMcpUrl() {
            const url = 'https://larafony.com/mcp';
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.querySelector('.copy-btn');
                const icon = btn.querySelector('i');
                icon.classList.remove('bi-clipboard');
                icon.classList.add('bi-check-lg');
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-light');
                setTimeout(() => {
                    icon.classList.remove('bi-check-lg');
                    icon.classList.add('bi-clipboard');
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-light');
                }, 2000);
            });
        }
    </script>
</body>
</html>
