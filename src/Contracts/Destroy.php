<?php

namespace BRCas\Laravel\Contracts;

interface Destroy
{
    public function find($id);

    public function destroy($obj);
}
