<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormLabel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(

        public ?string $for = null,     // 対象ID
        public string $width = 'w-28',
        public string $border = 'border-l-8',
        public string $color = 'border-l-brand',
        public string $class = ''
    )
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-label');
    }
}
