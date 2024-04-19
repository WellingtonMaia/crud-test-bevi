<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/
Route::middleware('json.response')->group(function () {
    Route::middleware('transaction')->group(function () {
        Route::apiResource('products', ProductController::class)
            ->only(['store', 'update', 'destroy'])
            ->names('products')
            ->parameters(['product' => 'id']);
    });

    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show'])
        ->names('products');
});

