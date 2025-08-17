<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Services\Products\ProductService;

class ProductControllerTest extends TestCase
{
    public function test_index_returns_ok(): void
    {
        // mock produtos
        $products = [
            [
                'id' => 1,
                'title' => 'Product 1',
                'price' => 10.5,
                'description' => 'Desc 1',
                'category' => 'cat',
                'image' => 'http://img/1.png',
                'rating' => ['rate' => 4.1, 'count' => 100],
            ],
            [
                'id' => 2,
                'title' => 'Product 2',
                'price' => 20,
                'description' => 'Desc 2',
                'category' => 'cat',
                'image' => 'http://img/2.png',
                'rating' => ['rate' => 3.9, 'count' => 50],
            ],
        ];

        $this->mock(ProductService::class, function ($mock) use ($products) {
            $mock->shouldReceive('index')->once()->andReturn($products);
        });

        $res = $this->getJson('/api/v1/products');
        $res->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id', 'title', 'price', 'description', 'category', 'image',
                        'rating' => ['rate', 'count']
                    ]
                ]
            ]);
    }

    public function test_show_returns_one(): void
    {
        $product = [
            'id' => 1,
            'title' => 'Product 1',
            'price' => 10.5,
            'description' => 'Desc 1',
            'category' => 'cat',
            'image' => 'http://img/1.png',
            'rating' => ['rate' => 4.1, 'count' => 100],
        ];

        $this->mock(ProductService::class, function ($mock) use ($product) {
            $mock->shouldReceive('show')->with(1)->once()->andReturn($product);
        });

        $res = $this->getJson('/api/v1/products/1');
        $res->assertOk()
            ->assertJsonStructure([
                'id', 'title', 'price', 'description', 'category', 'image',
                'rating' => ['rate', 'count']
            ]);
    }
}
