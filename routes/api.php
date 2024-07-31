<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
// add LogRequestMiddleware
Route::middleware(['auth:sanctum', 'logRequestMiddleware'])->group(function(){
    Route::apiResource('/products', ProductControllerAPI::class, ['names' => ['index' => 'api.products.index', 'store' => 'api.products.store', 'show' => 'api.products.show', 'update' => 'api.products.update', 'destroy' => 'api.products.destroy']]);
    Route::apiResource('/stores', StoreController::class, ['names' => ['index' => 'api.stores.index', 'store' => 'api.stores.store', 'show' => 'api.stores.show', 'update' => 'api.stores.update', 'destroy' => 'api.stores.destroy']])->middleware('isAdmin');
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

