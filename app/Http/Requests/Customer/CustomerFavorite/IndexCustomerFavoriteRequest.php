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
            'customer_id' => 'nullable|numeric|exists:customers,id',
            'product_id' => 'nullable|numeric',
        ];
    }
}
