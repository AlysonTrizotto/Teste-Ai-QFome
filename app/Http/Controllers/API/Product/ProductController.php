<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Services\Products\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){}

    public function index()
    {
        return response()->json($this->productService->index());
    }

    public function show(int $product)
    {
        return response()->json($this->productService->show($product));
    }
}
