<?php

namespace App\Http\Requests\Customer\CustomerFavorite;

use App\Models\Customer\CustomerFavorite;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueProductByUserRule;

class UpdateCustomerFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product_id' => [
                'nullable',
                'integer',
                new UniqueProductByUserRule(
                    $this->product_id, 
                    $this->customer_id,
                    $this->id
                )
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O campo id é obrigatório.',
            'id.integer' => 'O campo id deve ser um número inteiro.',
            'id.exists' => 'O id informado não existe.',
            'product_id.integer' => 'O campo produto deve ser um número inteiro.',
            'product_id.unique' => 'O produto já foi adicionado ao favoritos do cliente.',
            'customer_id.integer' => 'O campo cliente deve ser um número inteiro.',
            'customer_id.exists' => 'O cliente informado não existe.',
        ];
    }

    public function prepareForValidation()
    {
        parent::prepareForValidation();

        $idValue = null;
        $routeParameters = $this->route()->parameters();
        $parameterNames = $this->route()->parameterNames();

        if (!empty($parameterNames)) {
            if (count($parameterNames) == 1) {
                $paramName = head($parameterNames);
                $idValue = $routeParameters[$paramName] ?? null;
            }
            elseif (in_array('id', $parameterNames) && isset($routeParameters['id'])) {
                $idValue = $routeParameters['id'];
            }
        }

        if ($idValue !== null) {
            $actualId = ($idValue instanceof \Illuminate\Database\Eloquent\Model) ? $idValue->getKey() : $idValue;

            $this->merge([
                'id' => (int)$actualId,
            ]);
        }

        if(!$this->has('customer_id')){
            $customer_id = CustomerFavorite::where('id', $actualId)
                                            ->firstOrFail()
                                            ->customer_id;

            $this->merge([
                'customer_id' => $customer_id,
            ]);
        }
    }
}