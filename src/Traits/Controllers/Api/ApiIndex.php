<?php

namespace BRCas\Traits\Controllers\Api;

use BRCas\Traits\Queries\Index;
use Illuminate\Http\Request;

trait ApiIndex
{

    use Index;

    public abstract function index();

    protected abstract function resourceCollection();
}
