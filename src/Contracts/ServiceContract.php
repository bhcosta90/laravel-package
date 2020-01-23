<?php

namespace BRCas\Laravel\Contracts;

interface ServiceContract
{
    public static function store(array $dados);

    public static function update(object $obj, array $dados);
}
