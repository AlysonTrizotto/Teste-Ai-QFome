<?php

namespace App\Http\Requests\Customer\CustomerFavorite;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer',
            'product_id' => 'required|integer',
        ];
    }
}