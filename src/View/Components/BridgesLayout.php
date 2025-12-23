<?php

declare(strict_types=1);

namespace App\View\Components;

use Larafony\Framework\View\Component;

class BridgesLayout extends Component
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
    ) {
    }

    protected function getView(): string
    {
        return 'components.BridgesLayout';
    }
}
