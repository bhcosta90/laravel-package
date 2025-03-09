<?php

declare(strict_types = 1);

namespace CodeFusion\Service\Traits;

use CodeFusion\Service\Traits\Helper\WithIncludesTrait;
use CodeFusion\Service\Traits\Helper\{BaseQueryTrait};
use Illuminate\Database\Eloquent\{Model};

trait AsServiceShowTrait
{
    use WithIncludesTrait;
    use BaseQueryTrait;

    public function getById(string | int $id, array $includes = [], array $data = []): Model
    {
        $query = $this->baseQuery($data);

        $this->withIncludes($query, $includes);

        return $query->findOrFail($id);
    }
}
