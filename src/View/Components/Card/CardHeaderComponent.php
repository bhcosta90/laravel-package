<?php

namespace BRCas\Laravel\View\Components\Card;

use Illuminate\View\Component;

class CardHeaderComponent extends Component
{
    public function __construct(
        public string $title,
        public ?string $register = null,
        public ?string $textRegister = null,
        public ?string $typeRegister = 'link',
        public ?string $open = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('bhcosta90-package::components.card.header');
    }
}
