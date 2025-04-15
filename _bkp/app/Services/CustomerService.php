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

    public function beforeStore($attribute): array
    {
        $attribute['name'] .= ' - After Store';

        return $attribute;
    }

    public function beforeUpdate($attribute): array
    {
        $attribute['name'] .= ' - Update Store';

        return $attribute;
    }

    public function beforeSave($attribute): array
    {
        $attribute['name'] .= ' - After Save';

        return $attribute;
    }

    public function afterStore(): void
    {
        $name = substr($this->getModel()->name, 0, -14);
        $this->getModel()->update(compact('name'));
    }

    public function afterUpdate(): void
    {
        $name = substr($this->getModel()->name, 0, -15);
        $this->getModel()->update(compact('name'));
    }

    public function afterSave(): void
    {
        $name = substr($this->getModel()->name, 0, -13);
        $this->getModel()->update(compact('name'));
    }

    public function beforeDelete($model): void
    {
        $model->name .= ' - Before Delete';
        $model->save();
    }

    public function afterDelete($id): void
    {
        //
    }
}
