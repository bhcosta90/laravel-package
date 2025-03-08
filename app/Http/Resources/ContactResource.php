<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use CodeFusion\Resource\ValidateLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    use ValidateLoaded;

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'customer_id' => $this->customer_id,
            'name'        => $this->name,
            'customer'    => $this->verifyLoaded('customer', fn () => new CustomerResource($this->customer)),
        ];
    }
}
