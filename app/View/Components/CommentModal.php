<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Diary;

class CommentModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(

        public Diary $diary,
        public string $name = '',
        public string $maxWidth = 'md',
        public string $errorField = 'body',
        public bool $showButton = true,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment-modal');
    }
}
