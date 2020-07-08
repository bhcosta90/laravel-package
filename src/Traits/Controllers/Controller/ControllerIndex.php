<?php

namespace BRCas\Laravel\Traits\Controllers\Controller;

use BRCas\Laravel\Traits\Queries\Index;
use Illuminate\Http\Request;

trait ControllerIndex
{

    use Index;

    public abstract function index();

    protected abstract function resourceCollection();

}
