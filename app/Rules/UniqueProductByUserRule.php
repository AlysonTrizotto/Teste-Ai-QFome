<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\Customer\CustomerFavorite;

class UniqueProductByUserRule implements ValidationRule, DataAwareRule
{
    public $data = [];

    public function __construct(
        protected int $product_id,
        protected int $customer_id,
        protected ?int $id = null
    ) {}

    public function setData(array $data): void
    {
        $this->data = $data;
    }
    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->validateProduct($fail);
    }

    private function validateProduct(Closure $fail): void
    {
        $exists = CustomerFavorite::query()
                    ->where('product_id', $this->product_id)
                    ->where('customer_id', $this->customer_id)
                    ->whereNull('deleted_at')
                    ->when($this->id, fn($query) => $query->where('id', '!=', $this->id))
                    ->exists();

        if ($exists) {
            $fail('O produto já foi adicionado ao favoritos do cliente.');
        }
    }
}
