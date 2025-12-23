<?php

declare(strict_types=1);

namespace App\View\Components;

use Larafony\Framework\View\Component;

class Layout extends Component
{
    public function __construct(
        public readonly string $title = 'Larafony Framework - Modern PHP 8.5 Framework',
        public readonly string $description = 'Larafony is a modern PHP 8.5 framework built for clarity, not complexity. PSR-compliant, attribute-based, and production-ready.',
    ) {
    }

    protected function getView(): string
    {
        return 'components.Layout';
    }
}
