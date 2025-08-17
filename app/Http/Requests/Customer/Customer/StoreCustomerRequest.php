<?php

namespace App\Http\Requests\Customer\Customer;

use App\Trait\PrepareEmailToValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    use PrepareEmailToValidationTrait;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'email', 
                Rule::unique('customers', 'email')->where('deleted_at', null)
            ],
        ];
    }
}