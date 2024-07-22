<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class ProductControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $sql = 'select * from products';
        $product = Product::all();
        return response()->json([
            'status' => 'success',
            'products' => $product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);
        $product = Product::create($validatedData);
        return response()->json([
            'status' => 'success',
            'message' => 'Product created',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }else {
            return response()->json([
                'status' => 'success',
                'product' => $product
            ]);
        }
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }else {
            $product->update($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Product updated',
                'product' => $product
            ]);
            
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }else {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted'
            ]);
        }
    }
}
