<?php

namespace BRCas\Laravel\View\Components\Card;

use Illuminate\View\Component;

class CardFilterComponent extends Component
{
    public function __construct(
        public string $title,
        public array $filter,
    ) {
        //
    }

    public function render()
    {
        return view('bhcosta90-package::components.card.filter');
    }
}
