<?php

namespace BRCas\Laravel\Contracts;

interface Edit
{
    public function find($id);

    public function edit($obj, array $data);
}
