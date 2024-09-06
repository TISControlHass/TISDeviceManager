<?php

namespace Backpack\CRUD\app\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuSeparator extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return backpack_view('components.menu-separator');
    }
}
