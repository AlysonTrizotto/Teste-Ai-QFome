<?php

namespace App\Http\Requests\Customer\CustomerFavorite;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|integer',
            'product_id' => 'nullable|integer',
        ];
    }
}