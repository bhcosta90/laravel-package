<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\{Store};
use CodeFusion\Service\Traits\AsServiceIndexTrait;

class StoreService
{
    use AsServiceIndexTrait;

    protected function model(): string
    {
        return Store::class;
    }
}
