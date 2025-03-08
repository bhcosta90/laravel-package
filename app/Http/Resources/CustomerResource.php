<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use CodeFusion\Resource\ValidateLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    use ValidateLoaded;

    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'contacts' => $this->verifyLoaded('contacts', fn () => ContactResource::collection($this->contacts)),
        ];
    }
}
