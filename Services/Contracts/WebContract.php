<?php


namespace Costa\Package\Services\Contracts;


interface WebContract
{
    public function index($params): array;

    public function find($id);

    public function update($id, $data);

    public function store($data);

    public function destroy($id);
}
