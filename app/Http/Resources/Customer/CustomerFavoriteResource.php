<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerFavoriteResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}