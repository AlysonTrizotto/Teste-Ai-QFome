<?php

namespace App\Trait;

trait PrepareEmailToValidationTrait
{
    public function prepareForValidation()
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}
