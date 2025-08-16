<?php

namespace App\Http\Requests\Customer\CustomerFavorite;

use App\Rules\CheckIfProductExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueProductByUserRule;

class StoreCustomerFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', new CheckIfProductExistsRule()],
            'customer_id' => [
                'required', 
                'integer', 
                'exists:customers,id', 
                new UniqueProductByUserRule(
                    $this->product_id, 
                    $this->customer_id
                ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'O campo produto é obrigatório.',
            'product_id.integer' => 'O campo produto deve ser um número inteiro.',
            
            'customer_id.required' => 'O campo cliente é obrigatório.',
            'customer_id.integer' => 'O campo cliente deve ser um número inteiro.',
            'customer_id.exists' => 'O cliente informado não existe.',
            'customer_id.unique' => 'O cliente já possui este produto favorito.',
        ];
    }
}