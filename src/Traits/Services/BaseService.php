<?php

namespace BRCas\Laravel\Traits\Services;

trait BaseService
{
    public abstract function model();

    public function databaseList()
    {
        return $this->model()::all();
    }

    protected function databaseDestroy(object $object)
    {
        $object->delete();
    }

    protected function databaseCreated(object $obj, array $data=null)
    {
        return $obj;
    }

    protected function databaseUpdated(object $obj, array $dados=null)
    {
        return $obj;
    }
}
