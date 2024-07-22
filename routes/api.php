<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;
use App\Http\Controllers\StoreController;

Route::apiResource('/products', ProductControllerAPI::class, ['names' => ['index' => 'api.products.index', 'store' => 'api.products.store', 'show' => 'api.products.show', 'update' => 'api.products.update', 'destroy' => 'api.products.destroy']]);
Route::apiResource('/stores', StoreController::class, ['names' => ['index' => 'api.stores.index', 'store' => 'api.stores.store', 'show' => 'api.stores.show', 'update' => 'api.stores.update', 'destroy' => 'api.stores.destroy']]);
