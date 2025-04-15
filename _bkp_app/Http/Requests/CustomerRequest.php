<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'contacts'        => ['nullable', 'array'],
            'contacts.*.id'   => 'nullable|integer|exists:contacts,id',
            'contacts.*.name' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
