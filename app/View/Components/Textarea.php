<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Textarea extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?string $value = '',
        public ?int $rows = 3
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('components.textarea');
    }
}
