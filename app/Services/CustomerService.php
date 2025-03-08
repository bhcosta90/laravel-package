<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Customer;
use CodeFusion\Service\BaseService;

class CustomerService extends BaseService
{
    protected function model(): string
    {
        return Customer::class;
    }
}
