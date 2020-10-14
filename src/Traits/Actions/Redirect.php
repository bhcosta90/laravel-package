<?php


namespace BRCas\Laravel\Traits\Actions;


trait Redirect
{
    public function routeIndex()
    {
        return route($this->routeResource() . ".index");
    }

    /**
     * @return mixed
     */
    public abstract function routeResource();
}