<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();

        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category' => $data['category'],
            'image' => $data['image'],
            'rating' => [
                'rate' => $data['rating']['rate'] ?? null,
                'count' => $data['rating']['count'] ?? null,
            ],
        ];
    }
}
