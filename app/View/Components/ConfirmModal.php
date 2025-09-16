<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmModal extends Component
{
    public string $name; //モーダル識別子
    public string $title; // 見出し
    public string $message; // 本文
    public string $maxWidth; // 幅
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $name = 'confirm',
        string $title = '確認',
        string $message = '実行してよろしいですか？',
        string $maxWidth = '2xl'
        )
    {
        // 引数はデフォルト値を設定しているので省略しても動く。
        $this->name = $name;
        $this->title = $title;
        $this->message = $message;
        $this->maxWidth = $maxWidth;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.confirm-modal');
    }
}
