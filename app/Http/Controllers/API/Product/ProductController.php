<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductService;
use App\Http\Resources\Product\ProductResource;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){}

    public function index()
    {
        $data = $this->productService->index();
        return ProductResource::collection($data);
    }

    public function show(int $product)
    {
        $data = $this->productService->show($product);
        return ProductResource::make($data);
    }
}
