<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('/products', ProductControllerAPI::class, ['names' => ['index' => 'api.products.index', 'store' => 'api.products.store', 'show' => 'api.products.show', 'update' => 'api.products.update', 'destroy' => 'api.products.destroy']]);
    Route::apiResource('/stores', StoreController::class, ['names' => ['index' => 'api.stores.index', 'store' => 'api.stores.store', 'show' => 'api.stores.show', 'update' => 'api.stores.update', 'destroy' => 'api.stores.destroy']]);
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::fallback(function(){
    return response()->json(['message' => 'Unauthorized.'], 401);
});
   

