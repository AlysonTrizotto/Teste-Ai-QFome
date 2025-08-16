<?php

namespace App\Rules;

use App\Services\Products\ProductService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckIfProductExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($attribute != 'product_id'){
            return;
        }

        $service = app(ProductService::class);
        $product = $service->show($value);

        if((!$product || !is_array($product) || !isset($product['id'])) || $product['id'] != $value){
            $fail('O produto informado não existe.');
        }
    }
}
