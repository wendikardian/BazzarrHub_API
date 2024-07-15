<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;

Route::apiResource('/products', ProductControllerAPI::class);

