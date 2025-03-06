<?php

namespace CodeFusion\src\Service\Traits;

use CodeFusion\src\Service\Traits\Helper\{BaseQueryTrait};
use CodeFusion\src\Service\Traits\Helper\AddIncludesTrait;
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
