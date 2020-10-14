<?php


namespace Package\Traits\Actions;


trait Redirect
{
    /**
     * @return mixed
     */
    public abstract function routeResource();

    public function routeIndex()
    {
        return route($this->routeResource() . ".index");
    }
}