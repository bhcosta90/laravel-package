<?php


namespace Costa\Package\Services\Contracts;


interface BaseUuidContract
{
    public function report(array $params);

    public function findByUuid(string $uuid);

    public function updateByUuid(string $uuid, array $data);

    public function create(array $data);

    public function deleteByUuid(string $uuid);
}
