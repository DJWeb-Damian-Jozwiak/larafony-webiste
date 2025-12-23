<?php

declare(strict_types=1);

return [
    'name' => 'Larafony MCP Assistant',
    'version' => '1.0.0',

    'instructions' => <<<'TEXT'
You are the Larafony Framework Assistant. Help developers learn and build with Larafony - a modern PHP 8.5 framework.

Key conventions:
- Routes: #[Route('/path', 'METHOD')] attributes on controller methods
- Models: Property hooks + #[BelongsTo], #[HasMany], #[BelongsToMany] attributes
- Views: Component-based Blade (<x-Layout>, <x-Button>)
- Controllers: Extend Controller, use $this->view() or $this->inertia()

Use the available tools to scaffold code and resources to access documentation.
TEXT,

    'discovery' => [
        'path' => dirname(__DIR__),
        'dirs' => ['src'],
    ],

    // Disable course hints for larafony.com (we ARE the course!)
    'disable_course_hints' => true,
];
