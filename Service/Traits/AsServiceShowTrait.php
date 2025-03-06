<?php

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\{AddIncludesTrait, BaseQueryTrait};
use Illuminate\Database\Eloquent\{Model};

trait AsServiceShowTrait
{
    use AddIncludesTrait;
    use BaseQueryTrait;

    public function getById(string | int $id, array $includes = [], array $data = []): ?Model
    {
        $query = $this->baseQuery($data);

        $this->addIncludes($query, $includes);

        return $query->find($id);
    }
}
