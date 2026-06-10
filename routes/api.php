<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/warehouses', [WarehouseController::class, 'index']);

    Route::post('warehouses', [WarehouseController::class, 'store']);

    Route::put(
        '/warehouses/{warehouse}',
        [WarehouseController::class, 'update']
    );

    Route::delete(
        '/warehouses/{warehouse}',
        [WarehouseController::class, 'destroy']
    );

    Route::post(
        '/warehouses/{warehouse}/stock',
        [WarehouseController::class, 'updateStock']
    );

    Route::post('/products', [ProductController::class, 'store']);

    Route::get('/products', [ProductController::class, 'index']);

    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put(
        '/products/{product}',
        [ProductController::class, 'update']
    );
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get(
        '/orders',
        [OrderController::class, 'index']
    );
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
