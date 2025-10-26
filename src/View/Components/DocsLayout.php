<?php

declare(strict_types=1);

namespace App\View\Components;

use Larafony\Framework\View\Component;

class DocsLayout extends Component
{
    public function __construct(
        public readonly string $title = 'Documentation - Larafony Framework',
        public readonly string $description = 'Complete documentation for Larafony Framework - Modern PHP 8.5 framework',
    ) {
    }

    protected function getView(): string
    {
        return 'components.DocsLayout';
    }
}
