<?php

namespace App\Http\Requests\Customer\Customer;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'nullable|string|email|unique:customers,email,' . $this->route('customer'),
            'password' => 'nullable|string|max:255',
        ];
    }
}