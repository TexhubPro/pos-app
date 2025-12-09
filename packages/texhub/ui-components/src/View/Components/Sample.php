<?php

namespace Texhub\UiComponents\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Sample extends Component
{
    public function __construct(
        public string $title = 'UI Kit Sample',
        public string $message = 'Замените этот текст на свой компонент.'
    ) {
    }

    public function render(): View
    {
        return view('ui::components.sample');
    }
}
