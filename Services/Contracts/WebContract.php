<?php


namespace Costa\Package\Services\Contracts;


interface WebContract
{

    public function find($id);

    public function index($params): array;

    public function destroy($id);

    public function update($id, $data);

    public function store($data);
}
