<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}