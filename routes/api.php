<?php

use Illuminate\Support\Facades\Route;



Route::prefix('v1')->middleware(['throttle:10000,1'])->group(function () {
    Route::post('users/authenticate', [\App\Http\Controllers\API\User\Authenticable\AuthenticateController::class, 'authenticate']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('customers', \App\Http\Controllers\API\Customer\CustomerController::class);
        Route::apiResource('customer-favorites', \App\Http\Controllers\API\Customer\CustomerFavoriteController::class);
        Route::apiResource('products', \App\Http\Controllers\API\Product\ProductController::class)->only(['index', 'show']);
    });
});

