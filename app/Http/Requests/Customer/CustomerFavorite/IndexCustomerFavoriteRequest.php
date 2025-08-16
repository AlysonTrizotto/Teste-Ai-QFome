<?php

namespace App\Http\Requests\Customer\CustomerFavorite;

use Illuminate\Foundation\Http\FormRequest;

class IndexCustomerFavoriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|numeric|exists:customers,id',
            'product_id' => 'sometimes|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.integer' => 'O campo cliente deve ser um número inteiro.',
            'customer_id.exists' => 'O cliente informado não existe.',
            'product_id.integer' => 'O campo produto deve ser um número inteiro.',
        ];
    }
}
