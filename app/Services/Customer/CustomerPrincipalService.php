<?php

declare(strict_types = 1);

namespace App\Services\Customer;

use App\Models\Customer;
use CodeFusion\Service\BaseService;
use Illuminate\Database\Eloquent\Builder;

class CustomerPrincipalService extends BaseService
{
    protected function model(): string
    {
        return Customer::class;
    }

    protected function baseQuery(array $data = []): Builder
    {
        return parent::baseQuery($data)
            ->byType('principal');
    }
}
