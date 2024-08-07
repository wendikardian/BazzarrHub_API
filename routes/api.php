<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
// add LogRequestMiddleware
Route::middleware(['auth:sanctum', 'logRequestMiddleware'])->group(function(){
    Route::apiResource('/products', ProductControllerAPI::class, ['names' => ['index' => 'api.products.index', 'store' => 'api.products.store', 'show' => 'api.products.show', 'update' => 'api.products.update', 'destroy' => 'api.products.destroy']]);
    Route::apiResource('/stores', StoreController::class, ['names' => ['index' => 'api.stores.index', 'store' => 'api.stores.store', 'show' => 'api.stores.show', 'update' => 'api.stores.update', 'destroy' => 'api.stores.destroy']])->middleware('isAdmin');
    Route::apiResource('/brands', BrandController::class, ['names' => ['index' => 'api.brands.index', 'store' => 'api.brands.store', 'show' => 'api.brands.show', 'update' => 'api.brands.update', 'destroy' => 'api.brands.destroy']])->middleware('isAdmin');
    Route::apiResource('/categories', CategoryController::class, ['names' => ['index' => 'api.categories.index', 'store' => 'api.categories.store', 'show' => 'api.categories.show', 'update' => 'api.categories.update', 'destroy' => 'api.categories.destroy']]);
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::fallback(function(){
    return response()->json(['message' => 'Unauthorized.'], 401);
});
Route::group(['prefix' => 'v1'], function(){
    Route::get('/products', [ProductControllerAPI::class, 'index']);
    Route::get('/products/{id}', [ProductControllerAPI::class, 'show']);
    Route::post('/products', [ProductControllerAPI::class, 'store']);
    Route::put('/products/{id}', [ProductControllerAPI::class, 'update']);
    Route::delete('/products/{id}', [ProductControllerAPI::class, 'destroy']);
});

