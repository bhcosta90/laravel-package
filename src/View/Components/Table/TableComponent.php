<?php

namespace BRCas\Laravel\View\Components\Table;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class TableComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public array|LengthAwarePaginator $data,
        public array $table,
        public array $actions = [],
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
        return view('bhcosta90-package::components.table.table', [
            'data' => $this->data,
        ]);
    }
}
