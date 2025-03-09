<?php

declare(strict_types = 1);

namespace App\Services\Customer;

use App\Models\Customer;
use CodeFusion\Service\BaseService;

class CustomerContactPrincipalService extends BaseService
{
    protected function model(): string
    {
        return Customer::class;
    }

    protected function filterInclude(array $table): array
    {
        return [
            'contacts' => fn ($query) => $query->select($table['contacts'])->principal(),
        ];
    }
}
