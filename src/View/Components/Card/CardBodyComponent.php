<?php

namespace BRCas\Laravel\View\Components\Card;

use Illuminate\View\Component;

class CardBodyComponent extends Component
{
    public function __construct(
        public bool $show = true,
    ) {
        //
    }

    public function render()
    {
        return view('bhcosta90-package::components.card.body');
    }
}
