<?php


namespace Costa\Package\Services\Contracts;


interface ApiContract
{
    public function find($id);

    public function apiIndex($filter);

    public function apiStore($data);

    public function apiUpdate($id, $data);

    public function apiDestroy($id);
}
