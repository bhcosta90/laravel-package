<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\AddIncludesTrait;
use CodeFusion\Service\Traits\Helper\{BaseQueryTrait};
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
