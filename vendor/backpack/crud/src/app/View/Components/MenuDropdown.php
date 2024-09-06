<?php

namespace Backpack\CRUD\app\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuDropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $icon = null,
        public ?string $link = null,
        public bool $open = false,
        public array $items = [],
        public bool $nested = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return backpack_view('components.menu-dropdown');
    }
}
