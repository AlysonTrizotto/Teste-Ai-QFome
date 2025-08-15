<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// BEGIN: DDL-CRUD routes [Customer]
Route::prefix('v1')->middleware(['throttle:60,1'])->group(function () {
    Route::apiResource('customers', \App\Http\Controllers\API\Customer\CustomerController::class);
    Route::apiResource('customer-favorites', \App\Http\Controllers\API\Customer\CustomerFavoriteController::class);
});


