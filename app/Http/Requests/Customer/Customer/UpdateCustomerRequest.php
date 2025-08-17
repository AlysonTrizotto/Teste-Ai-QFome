<?php

namespace App\Http\Requests\Customer\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable', 
                'string', 
                'email', 
                Rule::unique('customers', 'email')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('customer'))
            ],
        ];
    }
}