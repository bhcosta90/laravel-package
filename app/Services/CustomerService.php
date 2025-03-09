<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Customer;
use CodeFusion\Service\BaseService;
use CodeFusion\Service\Support\UploadFile;

class CustomerService extends BaseService
{
    protected function model(): string
    {
        return Customer::class;
    }

    protected function beforeSave(array $data, ?Customer $customer = null): array
    {
        if (filled($data['photo'])) {
            $uploadFile = new UploadFile();

            $data['photo'] = $uploadFile->upload(
                $data['photo'],
                'customers/' . $customer?->id ?? str()->uuid()
            )->path;
        }

        return $data;
    }
}
