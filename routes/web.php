<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
// call index method from ProductController

// Route::apiResource('products', ProductController::class);


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/hello_world', 
//     function () {
//         return 'Hello World';
//     }
// );

Route::get('/products', 
    [ProductController::class, 'index']
)->name('products.index');
// form add product
Route::get('/products/create', 
    [ProductController::class, 'create']
)->name('products.create');
// form save product
Route::post('/products', 
    [ProductController::class, 'store']
)->name('products.store');

// show based on the id
Route::get('/products/{id}', 
    [ProductController::class, 'show']
)->name('products.show');

// form edit product
Route::get('/products/{id}/edit', 
    [ProductController::class, 'edit']
)->name('products.edit');

// update product based on id
Route::put('/products/{id}', 
    [ProductController::class, 'update']
)->name('products.update');

// delete product based on id

Route::delete('/products/{id}', 
    [ProductController::class, 'destroy']
)->name('products.destroy');