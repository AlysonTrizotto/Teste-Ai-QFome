<?php

namespace Tests\Unit\Product;

use Tests\TestCase;
use Mockery as m;
use App\Services\Products\ProductService;
use Illuminate\Support\Facades\Cache;

class ProductServiceTest extends TestCase
{
    // este teste usa mock http
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function test_index_returns_decoded_array(): void
    {
        config(['services.fakestoreapi.url' => 'http://fake']);

        $payload = [
            ['id' => 1, 'title' => 'A', 'price' => 10, 'description' => 'd', 'category' => 'c', 'image' => 'i', 'rating' => ['rate' => 4, 'count' => 2]],
        ];

        
        $curl = m::mock('App\\Helpers\\CurlHelper');

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(json_encode($payload));

        $service = new ProductService($curl);
        $result = $service->index();

        $this->assertIsArray($result);
        $this->assertSame($payload, $result);
    }

    public function test_show_returns_null_when_cache_returns_null(): void
    {
        config(['services.fakestoreapi.url' => 'http://fake']);

        $curl = m::mock('App\\Helpers\\CurlHelper');

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(null);

        $service = new ProductService($curl);
        $result = $service->show(123);

        $this->assertNull($result);
    }

    public function test_show_small_fields_returns_subset(): void
    {
        config(['services.fakestoreapi.url' => 'http://fake']);

        $full = [
            'id' => 5,
            'title' => 'T',
            'price' => 99.9,
            'description' => 'Desc',
            'category' => 'Cat',
            'image' => 'img',
            'rating' => ['rate' => 4.5, 'count' => 10],
        ];

        $curl = m::mock('App\\Helpers\\CurlHelper');

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(json_encode($full));

        $service = new ProductService($curl);
        $result = $service->showSmallFields(5);

        $this->assertSame([
            'id' => 5,
            'title' => 'T',
            'price' => 99.9,
            'image' => 'img',
            'rating' => ['rate' => 4.5, 'count' => 10],
        ], $result);
    }

    public function test_check_response_throws_on_non_200_http_code(): void
    {
        config(['services.fakestoreapi.url' => 'http://fake']);

        $curl = m::mock('App\\Helpers\\CurlHelper');
        $curl->shouldReceive('info')->andReturn(['http_code' => 500]);
        $curl->shouldReceive('error')->andReturn(null);

        $service = new ProductService($curl);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error fetching products_5');

        
        try {
            // teste com http code != 200
            $service->checkResponse('any', 'products_5');
        } finally {}
    }
}
