<?php

namespace BRCas\Traits\Controllers\Controller;

use BRCas\Traits\Queries\Index;
use Illuminate\Http\Request;

trait ControllerIndex
{

    use Index;

    public abstract function index();

    protected abstract function resourceCollection();

}
