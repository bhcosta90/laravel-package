<?php

declare(strict_types = 1);

namespace App\Http\Controller;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use CodeFusion\Controller\Traits\AsControllerTrait;

class CustomerController
{
    use AsControllerTrait;

    protected array $fieldSearchable = [
        'name',
    ];

    protected array $allowedFilters = [
        'name',
    ];

    protected array $allowedIncludes = [
        'contacts.customer',
    ];

    protected function service(): string
    {
        return CustomerService::class;
    }

    protected function resource(): string
    {
        return CustomerResource::class;
    }

    protected function request(): array
    {
        return [
            'store'  => CustomerRequest::class,
            'update' => CustomerRequest::class,
        ];
    }
}
