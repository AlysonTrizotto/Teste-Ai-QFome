<?php

namespace App\Http\Controllers\API\Product;

use App\Enum\HttpCodeEnum;
use App\Http\Controllers\Controller;
use App\Services\Products\ProductService;
use App\Http\Resources\Product\ProductResource;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){}

    public function index()
    {
        try {
            $data = $this->productService->index();
            return $this->sendSuccessResponse(ProductResource::collection($data), 'Products retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }

    public function show(int $product)
    {
        try {
            $data = $this->productService->show($product);
            return $this->sendSuccessResponse(new ProductResource($data), 'Product retrieved successfully', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }
}
