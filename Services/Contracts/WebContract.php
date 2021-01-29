<?php


namespace Costa\Package\Services\Contracts;


interface WebContract
{

    public function find($id);

    public function webIndex($filter): array;

    public function webDestroy($id);

    public function webUpdate($id, $data, $nameRoute);

    public function webStore($data, $nameRoute);
}
