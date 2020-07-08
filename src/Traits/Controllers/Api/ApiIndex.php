<?php

namespace BRCas\Laravel\Traits\Controllers\Api;

use BRCas\Laravel\Traits\Queries\Index;

trait ApiIndex
{

    use Index;

    public abstract function index();

    protected abstract function resourceCollection();
}
